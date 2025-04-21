<?php

namespace App\Notifications;

use App\Notifications\Contracts\Categorizable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class NotificationFailuresNeedAttention extends BaseNotification implements Categorizable
{
    /**
     * The notification failures.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected Collection $failures;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $failures)
    {
        $this->failures = $failures;
        $this->priority = 'high';
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'system_alert';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->error()
            ->subject('Notification Failures Need Attention')
            ->greeting("Hello {$notifiable->name}")
            ->line('The following notification failures require your attention:')
            ->line(new HtmlString($this->getFailuresDetailsHtml()))
            ->action('View Notification Failures', url('/admin/notification-failures'))
            ->line('Please review and resolve these failures as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'notification_failures_alert',
            'priority' => $this->priority,
            'failures_count' => $this->failures->count(),
            'failures' => $this->failures->map(function ($failure) {
                return [
                    'id' => $failure->id,
                    'channel' => $failure->channel,
                    'error_message' => $failure->error_message,
                    'attempts' => $failure->attempts,
                    'failed_at' => $failure->failed_at->toIso8601String(),
                ];
            }),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => 'Notification Failures Need Attention',
            'body' => "{$this->failures->count()} notification failures require your attention",
            'data' => $this->toArray($notifiable),
        ]);
    }

    /**
     * Get HTML representation of the failures details.
     */
    protected function getFailuresDetailsHtml(): string
    {
        $rows = $this->failures->map(function ($failure) {
            return sprintf(
                '<tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s/%d</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                </tr>',
                $failure->id,
                $failure->channel,
                $failure->error_message,
                $failure->attempts,
                \App\Models\NotificationFailure::MAX_ATTEMPTS,
                $failure->failed_at->format('Y-m-d H:i:s')
            );
        })->join('');

        return sprintf(
            '<div style="margin: 10px 0;">
                <table style="width: 100%%; border-collapse: collapse; border: 1px solid #e2e8f0;">
                    <thead>
                        <tr style="background-color: #f8fafc;">
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">ID</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Channel</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Error</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Attempts</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Failed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        %s
                    </tbody>
                </table>
                <p style="margin-top: 10px; color: #64748b; font-size: 0.875rem;">
                    Total Failures: %d
                </p>
            </div>',
            $rows,
            $this->failures->count()
        );
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable): array
    {
        // Always send through database and broadcast
        $channels = ['database', 'broadcast'];

        // Add mail for high-priority system alerts
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        return $channels;
    }
}
