<?php

namespace App\Console\Commands;

use App\Models\NotificationFailure;
use App\Models\NotificationSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clean
                          {--days=30 : Number of days to keep notifications}
                          {--dry-run : Show what would be deleted without actually deleting}
                          {--type=all : Type of notifications to clean (all/database/failures/schedules)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old notifications and related records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $isDryRun = $this->option('dry-run');
        $type = $this->option('type');

        $this->info("Starting notification cleanup...");
        $this->info("Retention period: {$days} days");
        
        if ($isDryRun) {
            $this->warn('DRY RUN - No records will be deleted');
        }

        try {
            $stats = [
                'database' => 0,
                'failures' => 0,
                'schedules' => 0,
            ];

            if (in_array($type, ['all', 'database'])) {
                $stats['database'] = $this->cleanDatabaseNotifications($days, $isDryRun);
            }

            if (in_array($type, ['all', 'failures'])) {
                $stats['failures'] = $this->cleanNotificationFailures($days, $isDryRun);
            }

            if (in_array($type, ['all', 'schedules'])) {
                $stats['schedules'] = $this->cleanNotificationSchedules($days, $isDryRun);
            }

            $this->displaySummary($stats, $isDryRun);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to clean notifications:');
            $this->error($e->getMessage());

            Log::error('Notification cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Clean database notifications.
     *
     * @param int $days
     * @param bool $isDryRun
     * @return int
     */
    protected function cleanDatabaseNotifications(int $days, bool $isDryRun): int
    {
        $query = DB::table('notifications')
            ->where('created_at', '<=', now()->subDays($days));

        $count = $query->count();

        if ($isDryRun) {
            $this->info("Would delete {$count} database notifications");
            $this->showSampleRecords('notifications', $query);
        } else {
            $query->delete();
            $this->info("Deleted {$count} database notifications");
        }

        return $count;
    }

    /**
     * Clean notification failures.
     *
     * @param int $days
     * @param bool $isDryRun
     * @return int
     */
    protected function cleanNotificationFailures(int $days, bool $isDryRun): int
    {
        $query = NotificationFailure::where(function ($query) use ($days) {
            $query->where('resolved_at', '<=', now()->subDays($days))
                ->orWhere(function ($query) use ($days) {
                    $query->where('failed_at', '<=', now()->subDays($days))
                        ->where('attempts', '>=', NotificationFailure::MAX_ATTEMPTS);
                });
        });

        $count = $query->count();

        if ($isDryRun) {
            $this->info("Would delete {$count} notification failures");
            $this->showSampleRecords('notification_failures', $query);
        } else {
            $query->delete();
            $this->info("Deleted {$count} notification failures");
        }

        return $count;
    }

    /**
     * Clean notification schedules.
     *
     * @param int $days
     * @param bool $isDryRun
     * @return int
     */
    protected function cleanNotificationSchedules(int $days, bool $isDryRun): int
    {
        $query = NotificationSchedule::where(function ($query) use ($days) {
            $query->where('frequency', 'once')
                ->where('active', false)
                ->where('last_sent_at', '<=', now()->subDays($days));
        });

        $count = $query->count();

        if ($isDryRun) {
            $this->info("Would delete {$count} notification schedules");
            $this->showSampleRecords('notification_schedules', $query);
        } else {
            $query->delete();
            $this->info("Deleted {$count} notification schedules");
        }

        return $count;
    }

    /**
     * Show sample records that would be deleted.
     *
     * @param string $table
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function showSampleRecords(string $table, $query): void
    {
        $samples = $query->limit(5)->get();

        if ($samples->isEmpty()) {
            return;
        }

        $this->info("\nSample records that would be deleted from {$table}:");

        $headers = array_keys((array) $samples->first());
        $rows = $samples->map(function ($record) {
            return array_map(function ($value) {
                return is_array($value) || is_object($value) ? json_encode($value) : $value;
            }, (array) $record);
        });

        $this->table($headers, $rows);
    }

    /**
     * Display cleanup summary.
     *
     * @param array $stats
     * @param bool $isDryRun
     * @return void
     */
    protected function displaySummary(array $stats, bool $isDryRun): void
    {
        $this->newLine();
        $this->info($isDryRun ? 'Cleanup Preview:' : 'Cleanup Summary:');
        
        $this->table(
            ['Type', 'Records ' . ($isDryRun ? 'to Delete' : 'Deleted')],
            [
                ['Database Notifications', $stats['database']],
                ['Notification Failures', $stats['failures']],
                ['Notification Schedules', $stats['schedules']],
                ['Total', array_sum($stats)],
            ]
        );

        if ($isDryRun) {
            $this->info('No records were actually deleted (dry run)');
        }
    }
}
