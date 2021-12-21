<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendBanUnbanNotification extends Notification
{
    use Queueable;

    public function __construct(
        private User $user
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $mailMessage = new MailMessage();
        $mailMessage->greeting('Hello ' . $this->user->name);

        if ($this->user->banned_until) {
            $mailMessage->subject(config('app.name') . ' - Inactive account')
                ->line('You account as been suspended.')
                ->line('We\'re sorry for the inconvinience');
        } else {
            $mailMessage->subject(config('app.name') . ' - Active account')
                ->line('Your account has been unlocked.')
                ->action('Login', route('login'))
                ->line('Thanks.');
        }

        return $mailMessage;
    }
}
