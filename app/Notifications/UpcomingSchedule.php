<?php

namespace App\Notifications;

use App\Models\GarbageSchedule;
use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Support\HtmlString;

class UpcomingSchedule extends BaseNotification implements Categorizable, ToSms
{
    /**
     * The garbage schedule.
     *
     * @var \App\Models\GarbageSchedule
     */
    protected GarbageSchedule $schedule;

    /**
     * Create a new notification instance.
     */
    public function __construct(GarbageSchedule $schedule)
    {
        $this->schedule = $schedule;
        $this->priority = 'high';
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'upcoming_schedule';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($this->getSubject())
            ->greeting("Hello {$notifiable->name}")
            ->line('You have an upcoming garbage collection schedule:')
            ->line(new HtmlString($this->getScheduleDetailsHtml()))
            ->action('View Schedule', url("/schedules/{$this->schedule->id}"))
            ->line('Please ensure all necessary preparations are made for the collection.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        return sprintf(
            "Upcoming Collection: %s at %s. Location: %s. Truck: %s",
            $this->schedule->scheduled_time->format('M j, Y'),
            $this->schedule->scheduled_time->format('g:i A'),
            $this->schedule->site->name,
            $this->schedule->truck_number
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'upcoming_schedule',
            'priority' => $this->priority,
            'schedule' => [
                'id' => $this->schedule->id,
                'scheduled_time' => $this->schedule->scheduled_time->toIso8601String(),
                'truck_number' => $this->schedule->truck_number,
                'site' => [
                    'id' => $this->schedule->site->id,
                    'name' => $this->schedule->site->name,
                    'latitude' => $this->schedule->site->latitude,
                    'longitude' => $this->schedule->site->longitude,
                ],
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
                'Upcoming collection at %s tomorrow at %s',
                $this->schedule->site->name,
                $this->schedule->scheduled_time->format('g:i A')
            ),
            'data' => $this->toArray($notifiable),
        ]);
    }

    /**
     * Get HTML representation of the schedule details.
     */
    protected function getScheduleDetailsHtml(): string
    {
        $site = $this->schedule->site;

        return sprintf(
            '<div style="margin: 10px 0; padding: 15px; border: 1px solid #e2e8f0; border-radius: 4px; background-color: #fff;">
                <div style="margin-bottom: 15px;">
                    <h3 style="margin: 0 0 5px 0; color: #1a56db;">Collection Details</h3>
                    <p style="margin: 0; color: #64748b;">
                        Scheduled for %s at %s
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong style="color: #1f2937;">Location:</strong>
                    <p style="margin: 5px 0;">%s</p>
                    <p style="margin: 5px 0; color: #64748b;">
                        Coordinates: %.6f, %.6f
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong style="color: #1f2937;">Truck Information:</strong>
                    <p style="margin: 5px 0;">Number: %s</p>
                </div>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                    <a href="%s" style="color: #2563eb; text-decoration: none;">
                        View on Google Maps
                    </a>
                </div>
            </div>',
            $this->schedule->scheduled_time->format('l, F j, Y'),
            $this->schedule->scheduled_time->format('g:i A'),
            $site->name,
            $site->latitude,
            $site->longitude,
            $this->schedule->truck_number,
            $this->getGoogleMapsUrl($site->latitude, $site->longitude)
        );
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        return sprintf(
            'Upcoming Garbage Collection - %s',
            $this->schedule->scheduled_time->format('M j, Y')
        );
    }

    /**
     * Get Google Maps URL for the location.
     */
    protected function getGoogleMapsUrl(float $latitude, float $longitude): string
    {
        return sprintf(
            'https://www.google.com/maps?q=%f,%f',
            $latitude,
            $longitude
        );
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable): array
    {
        // Always include database and broadcast for schedule notifications
        $channels = ['database', 'broadcast'];

        // Add mail if enabled
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS for workers assigned to the site
        if ($this->shouldSendSms($notifiable) && $this->isAssignedToSite($notifiable)) {
            $channels[] = 'sms';
        }

        return $channels;
    }

    /**
     * Determine if the user is assigned to the site.
     */
    protected function isAssignedToSite($notifiable): bool
    {
        if ($notifiable->role === 'admin') {
            return true;
        }

        return $notifiable->sites()
            ->where('sites.id', $this->schedule->site_id)
            ->exists();
    }
}
