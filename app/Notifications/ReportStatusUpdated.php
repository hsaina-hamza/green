<?php

namespace App\Notifications;

use App\Models\WasteReport;
use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ReportStatusUpdated extends BaseNotification implements Categorizable, ToSms
{
    /**
     * The waste report.
     *
     * @var \App\Models\WasteReport
     */
    protected WasteReport $report;

    /**
     * The previous status.
     *
     * @var string
     */
    protected string $previousStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(WasteReport $report, string $previousStatus)
    {
        $this->report = $report;
        $this->previousStatus = $previousStatus;
        $this->priority = $this->determinePriority();
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'status_update';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($this->getSubject())
            ->greeting("Hello {$notifiable->name}")
            ->line($this->getIntroduction());

        // Add status change details
        $mailMessage->line(new HtmlString($this->getStatusChangeHtml()));

        // Add report details
        $mailMessage->line(new HtmlString($this->getReportDetailsHtml()));

        // Add location details
        $mailMessage->line(new HtmlString($this->getLocationDetailsHtml()));

        // Add role-specific information
        if ($notifiable->role === 'admin') {
            $mailMessage->line('As an administrator, you can:')
                ->line('• Review the updated status and any comments')
                ->line('• Monitor progress and ensure timely completion')
                ->line('• Intervene if additional resources are needed');
        } elseif ($notifiable->role === 'worker') {
            if ($this->report->status === 'completed') {
                $mailMessage->line('Great job on completing this report!');
            } else {
                $mailMessage->line('Next steps:')
                    ->line('• Continue monitoring and updating the report status')
                    ->line('• Add comments for any issues or updates')
                    ->line('• Request assistance if needed');
            }
        } else {
            $mailMessage->line('You will be notified of further updates to this report.');
        }

        return $mailMessage
            ->action('View Report', url("/reports/{$this->report->id}"))
            ->line('Thank you for staying updated on this report.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        return sprintf(
            "Report #%d status changed from %s to %s. Location: %s. View at: %s",
            $this->report->id,
            Str::title($this->previousStatus),
            Str::title($this->report->status),
            $this->report->site->name,
            url("/reports/{$this->report->id}")
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'report_status_updated',
            'priority' => $this->priority,
            'report' => [
                'id' => $this->report->id,
                'type' => $this->report->type,
                'previous_status' => $this->previousStatus,
                'current_status' => $this->report->status,
                'site' => [
                    'id' => $this->report->site->id,
                    'name' => $this->report->site->name,
                ],
                'assigned_worker' => $this->report->assignedWorker ? [
                    'id' => $this->report->assignedWorker->id,
                    'name' => $this->report->assignedWorker->name,
                ] : null,
                'updated_at' => $this->report->updated_at->toIso8601String(),
            ],
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
     * Get HTML representation of the status change.
     */
    protected function getStatusChangeHtml(): string
    {
        $statusColors = [
            'pending' => '#dc2626',
            'in_progress' => '#2563eb',
            'completed' => '#059669',
        ];

        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="text-align: center; flex: 1;">
                        <div style="color: %s; font-weight: bold;">%s</div>
                        <div style="color: #64748b; font-size: 0.875rem;">Previous Status</div>
                    </div>
                    <div style="color: #64748b; padding: 0 20px;">→</div>
                    <div style="text-align: center; flex: 1;">
                        <div style="color: %s; font-weight: bold;">%s</div>
                        <div style="color: #64748b; font-size: 0.875rem;">Current Status</div>
                    </div>
                </div>
                <div style="margin-top: 15px; color: #64748b; text-align: center;">
                    Updated %s
                </div>
            </div>',
            $statusColors[$this->previousStatus] ?? '#64748b',
            Str::title($this->previousStatus),
            $statusColors[$this->report->status] ?? '#64748b',
            Str::title($this->report->status),
            $this->report->updated_at->diffForHumans()
        );
    }

    /**
     * Get HTML representation of the report details.
     */
    protected function getReportDetailsHtml(): string
    {
        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">Report Details</h3>
                <table style="width: 100%%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Report ID:</td>
                        <td style="padding: 8px 0;">#%d</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Type:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Assigned To:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Description:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                </table>
            </div>',
            $this->report->id,
            Str::title($this->report->type),
            $this->report->assignedWorker ? $this->report->assignedWorker->name : 'Unassigned',
            $this->report->description
        );
    }

    /**
     * Get HTML representation of the location details.
     */
    protected function getLocationDetailsHtml(): string
    {
        $site = $this->report->site;

        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">Location</h3>
                <p style="margin: 0 0 10px 0;">%s</p>
                <a href="https://www.google.com/maps?q=%f,%f" 
                   style="color: #2563eb; text-decoration: none;">
                    View on Google Maps
                </a>
            </div>',
            $site->name,
            $site->latitude,
            $site->longitude
        );
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        return sprintf(
            'Waste Report #%d Status Updated: %s',
            $this->report->id,
            Str::title($this->report->status)
        );
    }

    /**
     * Get the introduction message.
     */
    protected function getIntroduction(): string
    {
        return sprintf(
            'The status of waste report #%d has been updated from %s to %s.',
            $this->report->id,
            Str::title($this->previousStatus),
            Str::title($this->report->status)
        );
    }

    /**
     * Determine the notification priority.
     */
    protected function determinePriority(): string
    {
        // High priority for status changes to completed
        if ($this->report->status === 'completed') {
            return 'high';
        }

        // Normal priority for other status changes
        return 'normal';
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable): array
    {
        // Always include database and broadcast
        $channels = ['database', 'broadcast'];

        // Add mail for all status updates
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS for completed reports or if the user is an admin
        if ($this->shouldSendSms($notifiable) && 
            ($this->report->status === 'completed' || $notifiable->role === 'admin')) {
            $channels[] = 'sms';
        }

        return $channels;
    }
}
