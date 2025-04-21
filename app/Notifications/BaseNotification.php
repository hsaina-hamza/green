<?php

namespace App\Notifications;

use App\Notifications\Contracts\Categorizable;
use App\Notifications\Traits\HasSmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

abstract class BaseNotification extends Notification implements ShouldQueue, Categorizable
{
    use Queueable, HasSmsNotification;

    /**
     * The notification priority.
     *
     * @var string
     */
    protected string $priority = 'normal';

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        $channels = ['database', 'broadcast'];

        // Add mail channel if enabled
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS channel if enabled and notification implements ToSms
        if ($this instanceof Contracts\ToSms && $this->shouldSendSms($notifiable)) {
            $channels[] = 'sms';
        }

        return $channels;
    }

    /**
     * Determine if the notification should be sent via email.
     *
     * @param mixed $notifiable
     * @return bool
     */
    protected function shouldSendEmail($notifiable): bool
    {
        // Check if the notifiable has an email address
        if (!$notifiable->routeNotificationFor('mail', $this)) {
            return false;
        }

        // Check if the notifiable has email notifications enabled for this category
        if (method_exists($notifiable, 'shouldReceiveEmailNotification')) {
            return $notifiable->shouldReceiveEmailNotification($this);
        }

        // Check if the notifiable has email notifications enabled in their preferences
        if (method_exists($notifiable, 'notificationPreferences')) {
            $preferences = $notifiable->notificationPreferences()
                ->where('category', $this->category())
                ->where('channel', 'mail')
                ->first();

            if ($preferences) {
                return $preferences->enabled;
            }
        }

        // Check priority-based defaults
        $defaults = app('notification.preferences.defaults');
        return $defaults['priorities'][$this->priority]['mail'] ?? true;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'id' => (string) Str::uuid(),
            'type' => Str::snake(class_basename($this)),
            'category' => $this->category(),
            'priority' => $this->priority,
            'created_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags(): array
    {
        return [
            'notification:' . Str::snake(class_basename($this)),
            'category:' . $this->category(),
            'priority:' . $this->priority,
        ];
    }

    /**
     * Determine the notification's delivery delay.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function withDelay($notifiable): array
    {
        // High priority notifications are sent immediately
        if ($this->priority === 'high') {
            return [];
        }

        // Normal priority notifications can be delayed slightly
        if ($this->priority === 'normal') {
            return ['mail' => now()->addMinutes(5)];
        }

        // Low priority notifications are delayed more
        return [
            'mail' => now()->addHours(1),
            'sms' => now()->addHours(1),
        ];
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware(): array
    {
        return [
            new \Illuminate\Queue\Middleware\WithoutOverlapping($this->id),
            new \Illuminate\Queue\Middleware\RateLimited('notifications'),
        ];
    }

    /**
     * Get the retry delay for the notification.
     *
     * @return array
     */
    public function retryAfter(): array
    {
        return [
            'mail' => [1, 5, 15], // Retry after 1, 5, and 15 minutes
            'sms' => [1, 2, 5],   // Retry after 1, 2, and 5 minutes
        ];
    }

    /**
     * Get the maximum number of retries for the notification.
     *
     * @return int
     */
    public function maxRetries(): int
    {
        return 3;
    }

    /**
     * Determine if the notification should be retried.
     *
     * @param \Exception $exception
     * @return bool
     */
    public function shouldRetry(\Exception $exception): bool
    {
        return !($exception instanceof \Illuminate\Queue\MaxAttemptsExceededException);
    }

    /**
     * Get the notification's priority.
     *
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * Set the notification's priority.
     *
     * @param string $priority
     * @return $this
     */
    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }
}
