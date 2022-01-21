<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Maatwebsite\Excel\Events\ImportFailed;

class ImportHasFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $title,
        public ImportFailed $event
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = $this->event->getException()->getMessage();
        return (new MailMessage())
                    ->subject(config('app.name') . ' - You import of ' . $this->title . ' has failed!')
                    ->greeting('Hello ' . $this->user->name)
                    ->line('The import of file: ' . $this->title . ' has failed:')
                    ->line($message)
                    ->action('Login', route('login'))
                    ->line('Thank you!');
    }
}
