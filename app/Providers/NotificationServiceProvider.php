<?php

namespace App\Providers;

use App\Models\NotificationPreference;
use App\Models\User;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Container\Container;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the notification preferences defaults
        $this->app->singleton('notification.preferences.defaults', function ($app) {
            return [
                'channels' => [
                    'mail' => true,
                    'database' => true,
                    'broadcast' => true,
                    'sms' => false,
                ],
                'categories' => [
                    'new_report' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => false,
                    ],
                    'status_update' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => false,
                    ],
                    'assignment' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => true,
                    ],
                    'comment' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => false,
                    ],
                    'daily_digest' => [
                        'mail' => true,
                        'database' => false,
                        'broadcast' => false,
                        'sms' => false,
                    ],
                    'overdue_report' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => true,
                    ],
                    'unassigned_reports' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => false,
                    ],
                    'upcoming_schedule' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => true,
                    ],
                    'system_alert' => [
                        'mail' => true,
                        'database' => true,
                        'broadcast' => true,
                        'sms' => true,
                    ],
                ],
            ];
        });

        // Register the SMS channel configuration
        $this->app->singleton('notification.sms.config', function ($app) {
            return [
                'driver' => Config::get('services.sms.driver', 'twilio'),
                'from' => Config::get('services.sms.from'),
                'retry_times' => 3,
                'retry_delay' => 5,
                'options' => [
                    'verify_ssl' => true,
                ],
            ];
        });

        // Register the SMS channel
        $this->app->singleton('sms.channel', function ($app) {
            return new SmsChannel($app->make('notification.sms.config'));
        });

        // Register the channel manager with custom channels
        $this->app->singleton(ChannelManager::class, function ($app) {
            $manager = new ChannelManager($app);
            $manager->extend('sms', function ($app, $name) {
                return $app->make('sms.channel');
            });
            return $manager;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom notification channels
        $manager = $this->app->make(ChannelManager::class);

        // Register notification channel resolvers
        $this->registerChannelResolvers($manager);

        // Register notification event listeners
        $this->registerEventListeners();
    }

    /**
     * Register notification channel resolvers.
     */
    protected function registerChannelResolvers(ChannelManager $manager): void
    {
        // Resolve channels based on user preferences
        $manager->resolveChannelsUsing(function ($notifiable, $notification) {
            $channels = method_exists($notification, 'via')
                ? $notification->via($notifiable)
                : ['mail'];

            // Filter channels based on user preferences
            if (method_exists($notification, 'category')) {
                $category = $notification->category();
                $channels = $this->filterChannelsByPreferences($notifiable, $channels, $category);
            }

            return $channels;
        });
    }

    /**
     * Filter notification channels based on user preferences.
     */
    protected function filterChannelsByPreferences($notifiable, array $channels, string $category): array
    {
        if (!method_exists($notifiable, 'notificationPreferences')) {
            return $channels;
        }

        return collect($channels)->filter(function ($channel) use ($notifiable, $category) {
            // Check category-specific preference
            $categoryPreference = $notifiable->notificationPreferences()
                ->where('channel', $channel)
                ->where('category', $category)
                ->first();

            if ($categoryPreference) {
                return $categoryPreference->enabled;
            }

            // Check global channel preference
            $globalPreference = $notifiable->notificationPreferences()
                ->where('channel', $channel)
                ->whereNull('category')
                ->first();

            if ($globalPreference) {
                return $globalPreference->enabled;
            }

            // Fall back to defaults
            $defaults = $this->app->make('notification.preferences.defaults');
            return $defaults['categories'][$category][$channel] ?? 
                   $defaults['channels'][$channel] ?? 
                   false;
        })->values()->all();
    }

    /**
     * Register notification event listeners.
     */
    protected function registerEventListeners(): void
    {
        // Listen for new user creation to set up default preferences
        Event::listen('eloquent.created: ' . User::class, function ($event, $models) {
            if (!empty($models) && $models[0] instanceof User) {
                NotificationPreference::createDefaults($models[0]->id);
            }
        });

        // Listen for notification sending failures
        Event::listen(NotificationFailed::class, [$this, 'handleNotificationFailure']);
    }

    /**
     * Handle notification failures.
     */
    public function handleNotificationFailure(NotificationFailed $event): void
    {
        $notifiable = $event->notifiable;
        $notification = $event->notification;
        $channel = $event->channel;
        $data = $event->data;

        // Record the failure
        \App\Models\NotificationFailure::record(
            $notification->id ?? null,
            $channel,
            'Notification failed to send through ' . $channel . ' channel',
            [
                'notifiable_type' => get_class($notifiable),
                'notifiable_id' => $notifiable->getKey(),
                'notification_type' => get_class($notification),
                'data' => $data,
            ]
        );

        // Log the failure
        Log::error('Notification failed', [
            'channel' => $channel,
            'notification' => get_class($notification),
            'notifiable' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'data' => $data,
        ]);
    }
}
