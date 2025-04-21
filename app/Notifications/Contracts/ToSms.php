<?php

namespace App\Notifications\Contracts;

interface ToSms
{
    /**
     * Get the SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return string
     */
    public function toSms($notifiable): string;
}
