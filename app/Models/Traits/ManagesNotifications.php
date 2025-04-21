<?php

namespace App\Models\Traits;

use App\Models\NotificationPreference;
use App\Models\NotificationSchedule;
use App\Notifications\Contracts\Categorizable;
use Illuminate\Notifications\Notification;

trait ManagesNotifications
{
    /**
     * Get the notification preferences for the user.
     */
    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }

    /**
     * Get the notification schedules for the user.
     */
    public function notificationSchedules()
    {
        return $this->hasMany(NotificationSchedule::class);
    }

    /**
     * Route notifications for the SMS channel.
     *
     * @param \Illuminate\Notifications\Notification|null $notification
     * @return string|null
     */
    public function routeNotificationForSms(?Notification $notification = null): ?string
    {
        return $this->phone_number ? $this->formatPhoneNumber($this->phone_number) : null;
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
     * Determine if the user should receive SMS notifications.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return bool
     */
    public function shouldReceiveSmsNotification(Notification $notification): bool
    {
        // Check if user has a phone number
        if (!$this->phone_number) {
            return false;
        }

        // Get notification category if available
        $category = $notification instanceof Categorizable ? $notification->category() : null;

        // Check user preferences
        if ($category) {
            $preference = $this->notificationPreferences()
                ->where('category', $category)
                ->where('channel', 'sms')
                ->first();

            if ($preference) {
                return $preference->enabled;
            }
        }

        // Check global SMS preference
        $globalPreference = $this->notificationPreferences()
            ->whereNull('category')
            ->where('channel', 'sms')
            ->first();

        if ($globalPreference) {
            return $globalPreference->enabled;
        }

        // Default to system settings
        $defaults = app('notification.preferences.defaults');
        return $defaults['channels']['sms'] ?? false;
    }

    /**
     * Determine if the user should receive email notifications.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return bool
     */
    public function shouldReceiveEmailNotification(Notification $notification): bool
    {
        // Check if user has an email
        if (!$this->email) {
            return false;
        }

        // Get notification category if available
        $category = $notification instanceof Categorizable ? $notification->category() : null;

        // Check user preferences
        if ($category) {
            $preference = $this->notificationPreferences()
                ->where('category', $category)
                ->where('channel', 'mail')
                ->first();

            if ($preference) {
                return $preference->enabled;
            }
        }

        // Check global email preference
        $globalPreference = $this->notificationPreferences()
            ->whereNull('category')
            ->where('channel', 'mail')
            ->first();

        if ($globalPreference) {
            return $globalPreference->enabled;
        }

        // Default to system settings
        $defaults = app('notification.preferences.defaults');
        return $defaults['channels']['mail'] ?? true;
    }

    /**
     * Update notification preferences.
     *
     * @param array $preferences
     * @return void
     */
    public function updateNotificationPreferences(array $preferences): void
    {
        foreach ($preferences as $channel => $settings) {
            if (is_array($settings)) {
                // Category-specific settings
                foreach ($settings as $category => $enabled) {
                    $this->notificationPreferences()->updateOrCreate(
                        [
                            'channel' => $channel,
                            'category' => $category,
                        ],
                        ['enabled' => $enabled]
                    );
                }
            } else {
                // Global channel setting
                $this->notificationPreferences()->updateOrCreate(
                    [
                        'channel' => $channel,
                        'category' => null,
                    ],
                    ['enabled' => $settings]
                );
            }
        }
    }

    /**
     * Get all notification preferences with defaults.
     *
     * @return array
     */
    public function getAllNotificationPreferences(): array
    {
        $defaults = app('notification.preferences.defaults');
        $preferences = $this->notificationPreferences()->get();

        $result = [
            'channels' => array_fill_keys(array_keys($defaults['channels']), false),
            'categories' => [],
        ];

        // Set global channel preferences
        foreach ($preferences->where('category', null) as $pref) {
            $result['channels'][$pref->channel] = $pref->enabled;
        }

        // Set category-specific preferences
        foreach ($defaults['categories'] as $category => $channels) {
            $result['categories'][$category] = array_fill_keys(array_keys($channels), false);
            
            foreach ($preferences->where('category', $category) as $pref) {
                $result['categories'][$category][$pref->channel] = $pref->enabled;
            }
        }

        return $result;
    }

    /**
     * Schedule a notification.
     *
     * @param string $type
     * @param array $data
     * @param string $frequency
     * @param \Carbon\Carbon|null $scheduledAt
     * @return \App\Models\NotificationSchedule
     */
    public function scheduleNotification(
        string $type,
        array $data,
        string $frequency = 'once',
        ?\Carbon\Carbon $scheduledAt = null
    ): NotificationSchedule {
        return NotificationSchedule::schedule(
            $this,
            $type,
            $data,
            $frequency,
            $scheduledAt
        );
    }
}
