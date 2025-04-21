<?php

namespace App\Console\Commands;

use App\Models\NotificationFailure;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:process
                          {--chunk=10 : Number of notifications to process per batch}
                          {--retry : Also process failed notifications}
                          {--dry-run : Show what would be processed without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled notifications and optionally retry failed ones';

    /**
     * The notification service instance.
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $chunk = (int) $this->option('chunk');
        $isDryRun = $this->option('dry-run');
        $shouldRetry = $this->option('retry');

        $this->info("Starting notification processing...");

        try {
            // Process scheduled notifications
            $processed = $this->processScheduledNotifications($chunk, $isDryRun);
            $this->info("Processed {$processed} scheduled notifications.");

            // Process failed notifications if requested
            if ($shouldRetry) {
                $retried = $this->processFailedNotifications($chunk, $isDryRun);
                $this->info("Retried {$retried} failed notifications.");
            }

            // Display summary
            $this->displaySummary($processed, $shouldRetry ? $retried : 0);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to process notifications:');
            $this->error($e->getMessage());

            Log::error('Notification processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Process scheduled notifications.
     *
     * @param int $chunk
     * @param bool $isDryRun
     * @return int
     */
    protected function processScheduledNotifications(int $chunk, bool $isDryRun): int
    {
        if ($isDryRun) {
            $this->info('DRY RUN - Would process these notifications:');
            $this->displayScheduledNotifications($chunk);
            return 0;
        }

        return $this->notificationService->processScheduledNotifications($chunk);
    }

    /**
     * Process failed notifications.
     *
     * @param int $chunk
     * @param bool $isDryRun
     * @return int
     */
    protected function processFailedNotifications(int $chunk, bool $isDryRun): int
    {
        if ($isDryRun) {
            $this->info('DRY RUN - Would retry these failed notifications:');
            $this->displayFailedNotifications($chunk);
            return 0;
        }

        return $this->notificationService->retryFailedNotifications($chunk);
    }

    /**
     * Display scheduled notifications for dry run.
     *
     * @param int $chunk
     * @return void
     */
    protected function displayScheduledNotifications(int $chunk): void
    {
        $schedules = \App\Models\NotificationSchedule::due()
            ->with('user')
            ->limit($chunk)
            ->get();

        $this->table(
            ['ID', 'User', 'Type', 'Scheduled At', 'Frequency'],
            $schedules->map(function ($schedule) {
                return [
                    $schedule->id,
                    $schedule->user->name,
                    $schedule->notification_type,
                    $schedule->scheduled_at->format('Y-m-d H:i:s'),
                    $schedule->frequency,
                ];
            })
        );
    }

    /**
     * Display failed notifications for dry run.
     *
     * @param int $chunk
     * @return void
     */
    protected function displayFailedNotifications(int $chunk): void
    {
        $failures = NotificationFailure::readyForRetry()
            ->with('user')
            ->limit($chunk)
            ->get();

        $this->table(
            ['ID', 'User', 'Channel', 'Error', 'Attempts'],
            $failures->map(function ($failure) {
                return [
                    $failure->id,
                    $failure->user->name,
                    $failure->channel,
                    Str::limit($failure->error_message, 50),
                    "{$failure->attempts}/".NotificationFailure::MAX_ATTEMPTS,
                ];
            })
        );
    }

    /**
     * Display processing summary.
     *
     * @param int $processed
     * @param int $retried
     * @return void
     */
    protected function displaySummary(int $processed, int $retried): void
    {
        $this->newLine();
        $this->info('Processing Summary:');
        $this->table(
            ['Type', 'Count'],
            [
                ['Scheduled Notifications Processed', $processed],
                ['Failed Notifications Retried', $retried],
                ['Total', $processed + $retried],
            ]
        );

        // Display any notifications that need attention
        $needsAttention = NotificationFailure::getNeedsAttention();
        if ($needsAttention->isNotEmpty()) {
            $this->warn('The following notifications need attention:');
            $this->table(
                ['ID', 'Channel', 'Error', 'Failed At'],
                $needsAttention->map(function ($failure) {
                    return [
                        $failure->id,
                        $failure->channel,
                        Str::limit($failure->error_message, 50),
                        $failure->failed_at->format('Y-m-d H:i:s'),
                    ];
                })
            );
        }
    }
}
