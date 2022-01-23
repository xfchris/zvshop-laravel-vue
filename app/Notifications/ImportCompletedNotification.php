<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $title,
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
                    ->subject(config('app.name') . ' - You import of ' . $this->title . ' has been completed!')
                    ->greeting('Hello ' . $this->user->name)
                    ->line('The import of file: ' . $this->title . ' has been completed.')
                    ->line('You can enter the application for more details')
                    ->action('Login', route('login'))
                    ->line('Thank you!');
    }
}
