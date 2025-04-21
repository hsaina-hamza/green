<?php

namespace App\Notifications\Traits;

trait HasSmsNotification
{
    /**
     * Determine if the notification should be sent via SMS.
     *
     * @param mixed $notifiable
     * @return bool
     */
    protected function shouldSendSms($notifiable): bool
    {
        // Check if SMS notifications are enabled globally
        if (!config('services.sms.enabled', false)) {
            return false;
        }

        // Check if the notifiable has a phone number
        if (!$notifiable->routeNotificationFor('sms', $this)) {
            return false;
        }

        // Check if the notifiable has SMS notifications enabled for this category
        if (method_exists($notifiable, 'shouldReceiveSmsNotification')) {
            return $notifiable->shouldReceiveSmsNotification($this);
        }

        // Check if the notifiable has SMS notifications enabled in their preferences
        if (method_exists($notifiable, 'notificationPreferences')) {
            $preferences = $notifiable->notificationPreferences()
                ->where('category', $this->category())
                ->where('channel', 'sms')
                ->first();

            if ($preferences) {
                return $preferences->enabled;
            }
        }

        // Check priority-based defaults
        $defaults = app('notification.preferences.defaults');
        $priority = $this->priority ?? 'normal';

        return $defaults['priorities'][$priority]['sms'] ?? false;
    }

    /**
     * Get the notification's priority.
     *
     * @return string
     */
    protected function getPriority(): string
    {
        return $this->priority ?? 'normal';
    }

    /**
     * Format a phone number for SMS sending.
     *
     * @param string $number
     * @return string
     */
    protected function formatPhoneNumber(string $number): string
    {
        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Add country code if not present
        if (strlen($number) === 10) {
            $number = '1' . $number; // US/Canada country code
        }

        return '+' . $number;
    }

    /**
     * Truncate SMS message to fit within carrier limits.
     *
     * @param string $message
     * @param int $limit
     * @return string
     */
    protected function truncateSmsMessage(string $message, int $limit = 160): string
    {
        if (mb_strlen($message) <= $limit) {
            return $message;
        }

        return mb_substr($message, 0, $limit - 3) . '...';
    }

    /**
     * Get the notification's category.
     *
     * @return string
     */
    abstract public function category(): string;
}
