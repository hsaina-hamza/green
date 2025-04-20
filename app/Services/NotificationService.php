<?php

namespace App\Services;

use App\Models\NotificationFailure;
use App\Models\NotificationSchedule;
use App\Models\User;
use App\Models\WasteReport;
use App\Notifications\DailyDigest;
use App\Notifications\OverdueReport;
use App\Notifications\UnassignedReportsReminder;
use App\Notifications\UpcomingSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService extends BaseService
{
    /**
     * Send the daily digest notification.
     *
     * @param string $priority
     * @return void
     */
    public function sendDailyDigest(string $priority = 'normal'): void
    {
        $users = User::whereIn('role', ['admin', 'worker'])->get();

        foreach ($users as $user) {
            try {
                $reports = $this->getRelevantReportsForUser($user);
                
                if ($reports->isNotEmpty()) {
                    $user->notify((new DailyDigest($reports))->setPriority($priority));
                }
            } catch (\Exception $e) {
                $this->handleNotificationError($e, 'daily_digest', $user);
            }
        }
    }

    /**
     * Send notifications for overdue reports.
     *
     * @return void
     */
    public function sendOverdueReportNotifications(): void
    {
        $overdueReports = WasteReport::query()
            ->where('status', '!=', 'completed')
            ->where('created_at', '<=', now()->subDays(3))
            ->get();

        if ($overdueReports->isNotEmpty()) {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new OverdueReport($overdueReports));
                } catch (\Exception $e) {
                    $this->handleNotificationError($e, 'overdue_report', $admin);
                }
            }

            // Notify assigned workers
            $overdueReports->each(function ($report) {
                if ($report->assignedWorker) {
                    try {
                        $report->assignedWorker->notify(new OverdueReport(collect([$report])));
                    } catch (\Exception $e) {
                        $this->handleNotificationError($e, 'overdue_report', $report->assignedWorker);
                    }
                }
            });
        }
    }

    /**
     * Send notifications for unassigned reports.
     *
     * @return void
     */
    public function sendUnassignedReportsReminder(): void
    {
        $unassignedReports = WasteReport::whereNull('worker_id')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        if ($unassignedReports->isNotEmpty()) {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new UnassignedReportsReminder($unassignedReports));
                } catch (\Exception $e) {
                    $this->handleNotificationError($e, 'unassigned_reports', $admin);
                }
            }
        }
    }

    /**
     * Send notification for an upcoming schedule.
     *
     * @param \App\Models\GarbageSchedule $schedule
     * @return void
     */
    public function sendUpcomingScheduleNotification($schedule): void
    {
        $notification = new UpcomingSchedule($schedule);

        // Notify assigned workers
        $workers = User::where('role', 'worker')
            ->whereHas('sites', function ($query) use ($schedule) {
                $query->where('id', $schedule->site_id);
            })
            ->get();

        Notification::send($workers, $notification);

        // Notify site admin
        if ($admin = User::where('role', 'admin')->first()) {
            $admin->notify($notification);
        }
    }

    /**
     * Process scheduled notifications.
     *
     * @param int $chunk
     * @return int
     */
    public function processScheduledNotifications(int $chunk = 10): int
    {
        $processed = 0;
        $schedules = NotificationSchedule::due()->limit($chunk)->get();

        foreach ($schedules as $schedule) {
            try {
                DB::beginTransaction();

                $this->sendScheduledNotification($schedule);
                $schedule->markAsSent();
                $processed++;

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->handleNotificationError($e, 'scheduled', $schedule->user);
            }
        }

        return $processed;
    }

    /**
     * Retry failed notifications.
     *
     * @param int $chunk
     * @return int
     */
    public function retryFailedNotifications(int $chunk = 5): int
    {
        $retried = 0;
        $failures = NotificationFailure::readyForRetry()->limit($chunk)->get();

        foreach ($failures as $failure) {
            try {
                // Attempt to resend the notification
                $this->resendFailedNotification($failure);
                $failure->markAsResolved('Successfully retried');
                $retried++;
            } catch (\Exception $e) {
                $failure->recordRetry($e->getMessage());
            }
        }

        return $retried;
    }

    /**
     * Get reports relevant for a user's daily digest.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRelevantReportsForUser(User $user): Collection
    {
        $query = WasteReport::query()
            ->where('created_at', '>=', Carbon::yesterday())
            ->where('status', '!=', 'completed');

            if ($user->role === 'worker') {
            $query->where(function ($q) use ($user) {
                $q->where('worker_id', $user->id)
                    ->orWhereHas('site', function ($q) use ($user) {
                        $q->whereHas('workers', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    });
            });
        }

        return $query->get();
    }

    /**
     * Send a scheduled notification.
     *
     * @param \App\Models\NotificationSchedule $schedule
     * @return void
     */
    protected function sendScheduledNotification(NotificationSchedule $schedule): void
    {
        $notificationClass = $schedule->notification_type;
        $notification = new $notificationClass($schedule->notification_data);

        $schedule->user->notify($notification);
    }

    /**
     * Resend a failed notification.
     *
     * @param \App\Models\NotificationFailure $failure
     * @return void
     */
    protected function resendFailedNotification(NotificationFailure $failure): void
    {
        // Implementation would depend on how you store the original notification data
        // This is a simplified example
        if ($failure->notification_id) {
            $notification = DB::table('notifications')
                ->where('id', $failure->notification_id)
                ->first();

            if ($notification) {
                $notificationClass = $notification->type;
                $data = json_decode($notification->data, true);

                $failure->user->notify(new $notificationClass($data));
            }
        }
    }

    /**
     * Handle notification errors.
     *
     * @param \Exception $exception
     * @param string $type
     * @param \App\Models\User $user
     * @return void
     */
    protected function handleNotificationError(\Exception $exception, string $type, User $user): void
    {
        Log::error('Notification failed', [
            'type' => $type,
            'user_id' => $user->id,
            'error' => $exception->getMessage(),
        ]);

        NotificationFailure::record(
            null,
            $type,
            $exception->getMessage(),
            [
                'user_id' => $user->id,
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }
}
