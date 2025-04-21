<?php

namespace App\Notifications;

use App\Models\WasteReport;
use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class AssignedToReport extends BaseNotification implements Categorizable, ToSms
{
    /**
     * The waste report.
     *
     * @var \App\Models\WasteReport
     */
    protected WasteReport $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(WasteReport $report)
    {
        $this->report = $report;
        $this->priority = $this->determinePriority();
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'assignment';
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

        // Add report details
        $mailMessage->line(new HtmlString($this->getReportDetailsHtml()));

        // Add image if available
        if ($this->report->image_path) {
            $mailMessage->line(new HtmlString($this->getImageHtml()));
        }

        // Add location details with map
        $mailMessage->line(new HtmlString($this->getLocationDetailsHtml()));

        // Add required actions
        $mailMessage->line('Required Actions:')
            ->line('• Review the report details and location')
            ->line('• Plan necessary resources and equipment')
            ->line('• Update the report status when work begins')
            ->line('• Add comments for any questions or updates');

        // Add priority-specific message
        if ($this->priority === 'high') {
            $mailMessage->line(new HtmlString(
                '<div style="margin: 15px 0; padding: 10px; background-color: #fee2e2; border-radius: 4px; color: #dc2626;">
                    <strong>High Priority:</strong> This report requires immediate attention.
                </div>'
            ));
        }

        return $mailMessage
            ->action('View Report', url("/reports/{$this->report->id}"))
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        return sprintf(
            "%s: You've been assigned to waste report #%d at %s. Type: %s. View at: %s",
            $this->priority === 'high' ? 'URGENT' : 'New Assignment',
            $this->report->id,
            $this->report->site->name,
            $this->report->type,
            url("/reports/{$this->report->id}")
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'assigned_to_report',
            'priority' => $this->priority,
            'report' => [
                'id' => $this->report->id,
                'type' => $this->report->type,
                'description' => $this->report->description,
                'status' => $this->report->status,
                'image_url' => $this->report->image_path ? url($this->report->image_path) : null,
                'site' => [
                    'id' => $this->report->site->id,
                    'name' => $this->report->site->name,
                    'latitude' => $this->report->site->latitude,
                    'longitude' => $this->report->site->longitude,
                ],
                'assigned_at' => now()->toIso8601String(),
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
                        <td style="padding: 8px 0; color: #64748b;">Priority:</td>
                        <td style="padding: 8px 0;">
                            <span style="color: %s;">%s</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Description:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Submitted:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                </table>
            </div>',
            $this->report->id,
            Str::title($this->report->type),
            $this->getPriorityColor(),
            Str::title($this->priority),
            $this->report->description,
            $this->report->created_at->format('M j, Y g:i A')
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
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">Location Details</h3>
                <p style="margin: 0 0 10px 0;">%s</p>
                <p style="margin: 0 0 10px 0; color: #64748b;">
                    Coordinates: %.6f, %.6f
                </p>
                <div style="margin-top: 15px;">
                    <a href="https://www.google.com/maps?q=%f,%f" 
                       style="display: inline-block; padding: 8px 16px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 4px;">
                        View on Google Maps
                    </a>
                    <a href="https://www.google.com/maps/dir//%f,%f" 
                       style="display: inline-block; padding: 8px 16px; margin-left: 10px; background-color: #059669; color: white; text-decoration: none; border-radius: 4px;">
                        Get Directions
                    </a>
                </div>
            </div>',
            $site->name,
            $site->latitude,
            $site->longitude,
            $site->latitude,
            $site->longitude,
            $site->latitude,
            $site->longitude
        );
    }

    /**
     * Get HTML representation of the report image.
     */
    protected function getImageHtml(): string
    {
        return sprintf(
            '<div style="margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">Report Image</h3>
                <img src="%s" 
                     alt="Waste Report Image" 
                     style="max-width: 100%%; border-radius: 4px; border: 1px solid #e2e8f0;">
            </div>',
            url($this->report->image_path)
        );
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        return sprintf(
            '%sNew Assignment: Waste Report #%d',
            $this->priority === 'high' ? 'URGENT - ' : '',
            $this->report->id
        );
    }

    /**
     * Get the introduction message.
     */
    protected function getIntroduction(): string
    {
        return sprintf(
            'You have been assigned to handle waste report #%d at %s.',
            $this->report->id,
            $this->report->site->name
        );
    }

    /**
     * Determine the notification priority based on report type and other factors.
     */
    protected function determinePriority(): string
    {
        // High priority for hazardous waste
        if (Str::contains($this->report->type, ['hazardous', 'chemical', 'toxic'])) {
            return 'high';
        }

        // High priority for large volumes
        if (Str::contains($this->report->description, ['large', 'massive', 'significant'])) {
            return 'high';
        }

        // High priority for old reports
        if ($this->report->created_at->diffInHours() >= 48) {
            return 'high';
        }

        return 'normal';
    }

    /**
     * Get the color for the priority level.
     */
    protected function getPriorityColor(): string
    {
        return match($this->priority) {
            'high' => '#dc2626',
            'low' => '#059669',
            default => '#2563eb',
        };
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable): array
    {
        // Always include database and broadcast
        $channels = ['database', 'broadcast'];

        // Add mail for all assignments
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS for high priority assignments
        if ($this->shouldSendSms($notifiable) && $this->priority === 'high') {
            $channels[] = 'sms';
        }

        return $channels;
    }
}
