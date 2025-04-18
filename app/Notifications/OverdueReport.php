<?php

namespace App\Notifications;

use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class OverdueReport extends BaseNotification implements Categorizable, ToSms
{
    /**
     * The overdue reports.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected Collection $reports;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $reports)
    {
        $this->reports = $reports;
        $this->priority = 'high';
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'overdue_report';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->error()
            ->subject($this->getSubject())
            ->greeting("Hello {$notifiable->name}")
            ->line($this->getIntroduction());

        // Add reports table
        $mailMessage->line(new HtmlString($this->getReportsTableHtml()));

        // Add action items
        if ($notifiable->role === 'admin') {
            $mailMessage->line('As an administrator, you can:')
                ->line('• Reassign reports to different workers')
                ->line('• Update report priorities')
                ->line('• Contact workers for status updates');
        } else {
            $mailMessage->line('Please take action to:')
                ->line('• Update the status of your assigned reports')
                ->line('• Request assistance if needed')
                ->line('• Provide any blockers or issues preventing completion');
        }

        return $mailMessage
            ->action('View Overdue Reports', url('/reports?status=overdue'))
            ->line('Thank you for your prompt attention to these reports.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        $count = $this->reports->count();
        $oldestDays = $this->getOldestReportDays();

        return sprintf(
            "ALERT: %d waste %s overdue (oldest: %d days). Immediate attention required. View at: %s",
            $count,
            Str::plural('report', $count),
            $oldestDays,
            url('/reports?status=overdue')
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'overdue_report',
            'priority' => $this->priority,
            'reports_count' => $this->reports->count(),
            'oldest_report_days' => $this->getOldestReportDays(),
            'reports' => $this->reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'type' => $report->type,
                    'status' => $report->status,
                    'days_overdue' => $report->created_at->diffInDays(),
                    'site' => [
                        'id' => $report->site->id,
                        'name' => $report->site->name,
                    ],
                    'assigned_worker' => $report->assignedWorker ? [
                        'id' => $report->assignedWorker->id,
                        'name' => $report->assignedWorker->name,
                    ] : null,
                    'created_at' => $report->created_at->toIso8601String(),
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
            'title' => $this->getSubject(),
            'body' => $this->getIntroduction(),
            'data' => $this->toArray($notifiable),
        ]);
    }

    /**
     * Get HTML representation of the reports table.
     */
    protected function getReportsTableHtml(): string
    {
        if ($this->reports->isEmpty()) {
            return '<p style="color: #64748b;">No overdue reports to display.</p>';
        }

        $rows = $this->reports->map(function ($report) {
            $daysOverdue = $report->created_at->diffInDays();
            $urgencyColor = match(true) {
                $daysOverdue >= 7 => '#dc2626', // red for >= 7 days
                $daysOverdue >= 5 => '#ea580c', // orange for >= 5 days
                default => '#d97706', // amber for < 5 days
            };

            return sprintf(
                '<tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">#%d</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; color: %s;">
                        <strong>%d days</strong>
                    </td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                </tr>',
                $report->id,
                $report->type,
                $report->site->name,
                Str::title($report->status),
                $urgencyColor,
                $daysOverdue,
                $report->assignedWorker ? $report->assignedWorker->name : 'Unassigned'
            );
        })->join('');

        return sprintf(
            '<div style="margin: 20px 0; overflow-x: auto;">
                <table style="width: 100%%; border-collapse: collapse; border: 1px solid #e2e8f0;">
                    <thead>
                        <tr style="background-color: #fee2e2;">
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">ID</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Type</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Location</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Status</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Overdue</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Assigned To</th>
                        </tr>
                    </thead>
                    <tbody>
                        %s
                    </tbody>
                </table>
            </div>',
            $rows
        );
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        $count = $this->reports->count();
        return sprintf(
            'URGENT: %d Waste %s Overdue',
            $count,
            Str::plural('Report', $count)
        );
    }

    /**
     * Get the introduction message.
     */
    protected function getIntroduction(): string
    {
        $count = $this->reports->count();
        $oldestDays = $this->getOldestReportDays();

        return sprintf(
            'You have %d overdue waste %s that require immediate attention. The oldest report is %d days overdue.',
            $count,
            Str::plural('report', $count),
            $oldestDays
        );
    }

    /**
     * Get the number of days the oldest report is overdue.
     */
    protected function getOldestReportDays(): int
    {
        if ($this->reports->isEmpty()) {
            return 0;
        }

        return $this->reports->max(function ($report) {
            return $report->created_at->diffInDays();
        });
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable): array
    {
        // Always include database and broadcast for overdue notifications
        $channels = ['database', 'broadcast'];

        // Add mail for all overdue notifications
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS for assigned workers and admins
        if ($this->shouldSendSms($notifiable) && $this->isDirectlyInvolved($notifiable)) {
            $channels[] = 'sms';
        }

        return $channels;
    }

    /**
     * Determine if the user is directly involved with any of the reports.
     */
    protected function isDirectlyInvolved($notifiable): bool
    {
        if ($notifiable->role === 'admin') {
            return true;
        }

        return $this->reports->contains(function ($report) use ($notifiable) {
            return $report->assigned_worker_id === $notifiable->id;
        });
    }
}
