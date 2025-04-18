<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'channel',
        'category',
        'enabled',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * The available notification channels.
     *
     * @var array<string>
     */
    public static array $channels = [
        'mail',
        'sms',
        'database',
        'broadcast',
    ];

    /**
     * The available notification categories.
     *
     * @var array<string>
     */
    public static array $categories = [
        'new_report',
        'status_update',
        'assignment',
        'comment',
        'daily_digest',
        'overdue_report',
        'unassigned_reports',
        'upcoming_schedule',
    ];

    /**
     * Get the user that owns the notification preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include preferences for a specific channel.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $channel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Scope a query to only include preferences for a specific category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include enabled preferences.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope a query to only include disabled preferences.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    }

    /**
     * Get the default settings for a channel.
     *
     * @param string $channel
     * @return array
     */
    public static function getDefaultSettings(string $channel): array
    {
        $defaults = app('notification.preferences.defaults');
        return $defaults['channels'][$channel] ?? [];
    }

    /**
     * Get the default enabled state for a channel and category.
     *
     * @param string $channel
     * @param string|null $category
     * @return bool
     */
    public static function getDefaultEnabled(string $channel, ?string $category = null): bool
    {
        $defaults = app('notification.preferences.defaults');

        if ($category) {
            return $defaults['categories'][$category][$channel] ?? 
                   $defaults['channels'][$channel] ?? 
                   false;
        }

        return $defaults['channels'][$channel] ?? false;
    }

    /**
     * Create default preferences for a user.
     *
     * @param int $userId
     * @return void
     */
    public static function createDefaults(int $userId): void
    {
        $defaults = app('notification.preferences.defaults');

        // Create global channel preferences
        foreach ($defaults['channels'] as $channel => $enabled) {
            self::firstOrCreate(
                [
                    'user_id' => $userId,
                    'channel' => $channel,
                    'category' => null,
                ],
                [
                    'enabled' => $enabled,
                    'settings' => self::getDefaultSettings($channel),
                ]
            );
        }

        // Create category-specific preferences
        foreach ($defaults['categories'] as $category => $channels) {
            foreach ($channels as $channel => $enabled) {
                self::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'channel' => $channel,
                        'category' => $category,
                    ],
                    [
                        'enabled' => $enabled,
                        'settings' => self::getDefaultSettings($channel),
                    ]
                );
            }
        }
    }

    /**
     * Reset preferences to defaults.
     *
     * @return void
     */
    public function resetToDefaults(): void
    {
        $this->enabled = self::getDefaultEnabled($this->channel, $this->category);
        $this->settings = self::getDefaultSettings($this->channel);
        $this->save();
    }

    /**
     * Update settings while preserving existing values.
     *
     * @param array $settings
     * @return void
     */
    public function updateSettings(array $settings): void
    {
        $this->settings = array_merge($this->settings ?? [], $settings);
        $this->save();
    }
}
