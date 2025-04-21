<?php

namespace App\Notifications;

use App\Models\WasteReport;
use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class NewWasteReport extends BaseNotification implements Categorizable, ToSms
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
        return 'new_report';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($this->getSubject())
            ->greeting("Hello {$notifiable->name}")
            ->line('A new waste report has been submitted:');

        // Add report details
        $mailMessage->line(new HtmlString($this->getReportDetailsHtml()));

        // Add image if available
        if ($this->report->image_path) {
            $mailMessage->line(new HtmlString($this->getImageHtml()));
        }

        // Add location details
        $mailMessage->line(new HtmlString($this->getLocationDetailsHtml()));

        // Add action items based on role
        if ($notifiable->role === 'admin') {
            $mailMessage->line('Required Actions:')
                ->line('• Review the report details')
                ->line('• Assign a worker to handle the report')
                ->line('• Set appropriate priority if needed');
        } elseif ($notifiable->role === 'worker') {
            $mailMessage->line('Required Actions:')
                ->line('• Review the report location and details')
                ->line('• Assess the situation and required resources')
                ->line('• Update the report status when work begins');
        }

        return $mailMessage
            ->action('View Report', url("/reports/{$this->report->id}"))
            ->line('Thank you for your prompt attention to this matter.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        return sprintf(
            "New waste report #%d at %s. Type: %s. Priority: %s. View at: %s",
            $this->report->id,
            $this->report->site->name,
            $this->report->type,
            $this->priority,
            url("/reports/{$this->report->id}")
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_waste_report',
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
                'created_at' => $this->report->created_at->toIso8601String(),
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
            'body' => sprintf(
                'New %s waste report at %s',
                $this->report->type,
                $this->report->site->name
            ),
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
                <a href="https://www.google.com/maps?q=%f,%f" 
                   style="display: inline-block; padding: 8px 16px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 4px;">
                    View on Google Maps
                </a>
            </div>',
            $site->name,
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
            'New Waste Report: %s at %s',
            Str::title($this->report->type),
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

        // Add mail for all new reports
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS for high priority reports or if the user is an admin
        if ($this->shouldSendSms($notifiable) && 
            ($this->priority === 'high' || $notifiable->role === 'admin')) {
            $channels[] = 'sms';
        }

        return $channels;
    }
}
