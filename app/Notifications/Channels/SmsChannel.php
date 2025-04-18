<?php

namespace App\Notifications\Channels;

use App\Models\NotificationFailure;
use App\Notifications\Contracts\ToSms;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    /**
     * The SMS service API key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The default "from" number.
     *
     * @var string
     */
    protected string $from;

    /**
     * Create a new SMS channel instance.
     */
    public function __construct(string $apiKey, string $from)
    {
        $this->apiKey = $apiKey;
        $this->from = $from;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification): void
    {
        if (!$this->shouldSend($notifiable, $notification)) {
            return;
        }

        if (!$to = $notifiable->routeNotificationFor('sms', $notification)) {
            return;
        }

        $message = $this->formatMessage($notification, $notifiable);

        try {
            $response = $this->sendSms($to, $message);
            $this->handleResponse($response, $notifiable, $notification);
        } catch (\Exception $e) {
            $this->handleError($e, $notifiable, $notification);
        }
    }

    /**
     * Determine if the notification should be sent.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return bool
     */
    protected function shouldSend($notifiable, Notification $notification): bool
    {
        if (!$notification instanceof ToSms) {
            return false;
        }

        if (!config('services.sms.enabled', false)) {
            return false;
        }

        return true;
    }

    /**
     * Format the notification message.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @param mixed $notifiable
     * @return string
     */
    protected function formatMessage(Notification $notification, $notifiable): string
    {
        $message = $notification->toSms($notifiable);

        // Add prefix if configured
        if ($prefix = config('services.sms.prefix')) {
            $message = $prefix . ' ' . $message;
        }

        // Add suffix if configured
        if ($suffix = config('services.sms.suffix')) {
            $message .= ' ' . $suffix;
        }

        return $message;
    }

    /**
     * Send the SMS message.
     *
     * @param string $to
     * @param string $message
     * @return array
     */
    protected function sendSms(string $to, string $message): array
    {
        // This is a placeholder implementation. Replace with your actual SMS service.
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post(config('services.sms.endpoint'), [
            'from' => $this->from,
            'to' => $to,
            'message' => $message,
        ]);

        if (!$response->successful()) {
            throw new \Exception('SMS service error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Handle the API response.
     *
     * @param array $response
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    protected function handleResponse(array $response, $notifiable, Notification $notification): void
    {
        // Log successful send
        Log::info('SMS notification sent', [
            'notifiable' => get_class($notifiable) . ':' . $notifiable->id,
            'notification' => get_class($notification),
            'response' => $response,
        ]);
    }

    /**
     * Handle any errors that occurred.
     *
     * @param \Exception $exception
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    protected function handleError(\Exception $exception, $notifiable, Notification $notification): void
    {
        Log::error('SMS notification failed', [
            'notifiable' => get_class($notifiable) . ':' . $notifiable->id,
            'notification' => get_class($notification),
            'error' => $exception->getMessage(),
        ]);

        // Record the failure
        NotificationFailure::record(
            $notification->id ?? null,
            'sms',
            $exception->getMessage(),
            [
                'notifiable_type' => get_class($notifiable),
                'notifiable_id' => $notifiable->id,
                'notification_type' => get_class($notification),
            ]
        );

        // Optionally rethrow the exception if configured to do so
        if (config('services.sms.throw_exceptions', false)) {
            throw $exception;
        }
    }

    /**
     * Get the default "from" number.
     *
     * @return string
     */
    public function getDefaultFrom(): string
    {
        return $this->from;
    }

    /**
     * Set the default "from" number.
     *
     * @param string $from
     * @return $this
     */
    public function setDefaultFrom(string $from): self
    {
        $this->from = $from;
        return $this;
    }
}
