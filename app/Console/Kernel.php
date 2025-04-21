<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process scheduled notifications every minute
        $schedule->command('notifications:process')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // Send daily digest notifications at 8 AM
        $schedule->command('notifications:daily-digest')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Clean up old notifications weekly
        $schedule->command('notifications:clean --days=30')
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->withoutOverlapping();

        // Retry failed notifications every 15 minutes
        $schedule->command('notifications:process --retry')
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        // Send overdue report notifications daily at 9 AM
        $schedule->call(function () {
            app(\App\Services\NotificationService::class)->sendOverdueReportNotifications();
        })->dailyAt('09:00')
          ->withoutOverlapping();

        // Send unassigned reports reminder at 10 AM on weekdays
        $schedule->call(function () {
            app(\App\Services\NotificationService::class)->sendUnassignedReportsReminder();
        })->weekdays()
          ->at('10:00')
          ->withoutOverlapping();

        // Send upcoming schedule notifications 24 hours before
        $schedule->call(function () {
            $tomorrow = now()->addDay();
            $schedules = \App\Models\GarbageSchedule::whereDate('scheduled_time', $tomorrow->toDateString())->get();
            
            foreach ($schedules as $schedule) {
                app(\App\Services\NotificationService::class)->sendUpcomingScheduleNotification($schedule);
            }
        })->dailyAt('11:00')
          ->withoutOverlapping();

        // Monitor notification failures that need attention
        $schedule->call(function () {
            $needsAttention = \App\Models\NotificationFailure::getNeedsAttention();
            
            if ($needsAttention->isNotEmpty()) {
                // Notify administrators about failures that need attention
                \App\Models\User::where('role', 'admin')->get()->each(function ($admin) use ($needsAttention) {
                    $admin->notify(new \App\Notifications\NotificationFailuresNeedAttention($needsAttention));
                });
            }
        })->hourly()
          ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     */
    protected function scheduleTimezone(): string
    {
        return config('app.timezone', 'UTC');
    }

    /**
     * Define the application's command schedule monitor.
     */
    protected function scheduleMonitor(): array
    {
        return [
            'notifications:process' => [
                'email' => config('notification.monitor.email'),
                'slack' => config('notification.monitor.slack'),
            ],
            'notifications:daily-digest' => [
                'email' => config('notification.monitor.email'),
                'slack' => config('notification.monitor.slack'),
            ],
            'notifications:clean' => [
                'email' => config('notification.monitor.email'),
                'slack' => config('notification.monitor.slack'),
            ],
        ];
    }

    /**
     * Get the maintenance mode driver.
     */
    protected function maintenanceModeDriver(): string
    {
        return 'file';
    }
}
