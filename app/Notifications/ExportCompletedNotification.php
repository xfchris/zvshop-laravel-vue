<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $title,
        public string $url,
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
                    ->subject(config('app.name') . ' - You report of ' . $this->title . ' has been completed!')
                    ->greeting('Hello ' . $this->user->name)
                    ->line('Here is the link to download you report of ' . $this->title . '.')
                    ->action('Download', $this->url)
                    ->line('The file will be available only for ' . config('constants.reports_expiration_days') . ' days.')
                    ->line('Thank you!');
    }
}
