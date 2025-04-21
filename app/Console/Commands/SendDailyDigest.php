<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:daily-digest
                          {--priority=normal : Priority level for the notifications (low/normal/high)}
                          {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily digest notifications to users';

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
        $priority = $this->option('priority');
        $isDryRun = $this->option('dry-run');

        if (!in_array($priority, ['low', 'normal', 'high'])) {
            $this->error('Invalid priority level. Must be one of: low, normal, high');
            return Command::FAILURE;
        }

        $this->info("Starting daily digest processing...");
        $this->info("Priority: {$priority}");

        try {
            if ($isDryRun) {
                $this->performDryRun();
                return Command::SUCCESS;
            }

            $this->notificationService->sendDailyDigest($priority);
            $this->info('Daily digest notifications have been sent successfully.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to send daily digest notifications:');
            $this->error($e->getMessage());

            Log::error('Daily digest processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Perform a dry run to show what would be sent.
     *
     * @return void
     */
    protected function performDryRun(): void
    {
        $this->info('DRY RUN - Would send daily digest to these users:');

        $users = \App\Models\User::whereIn('role', ['admin', 'worker'])->get();
        
        $data = $users->map(function ($user) {
            $reports = $this->getRelevantReportsForUser($user);
            return [
                $user->id,
                $user->name,
                $user->role,
                $user->email,
                $reports->count(),
            ];
        });

        $this->table(
            ['ID', 'Name', 'Role', 'Email', 'Report Count'],
            $data
        );

        // Show sample report data for the first user
        if ($users->isNotEmpty()) {
            $firstUser = $users->first();
            $reports = $this->getRelevantReportsForUser($firstUser);

            if ($reports->isNotEmpty()) {
                $this->info("\nSample report data for {$firstUser->name}:");
                $this->table(
                    ['ID', 'Type', 'Status', 'Location', 'Created'],
                    $reports->take(5)->map(function ($report) {
                        return [
                            $report->id,
                            $report->type,
                            $report->status,
                            $report->site->name,
                            $report->created_at->format('Y-m-d H:i:s'),
                        ];
                    })
                );
            }
        }
    }

    /**
     * Get reports relevant for a user's daily digest.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRelevantReportsForUser($user)
    {
        $query = \App\Models\WasteReport::query()
            ->where('created_at', '>=', now()->subDay())
            ->where('status', '!=', 'completed');

        if ($user->role === 'worker') {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_worker_id', $user->id)
                    ->orWhereHas('site', function ($q) use ($user) {
                        $q->whereHas('workers', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    });
            });
        }

        return $query->with('site')->get();
    }
}
