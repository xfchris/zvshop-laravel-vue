<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
                    ->subject(config('app.name') . ' - The status of your order has changed!')
                    ->greeting('Hello ' . $this->order->user->name)
                    ->line('Your order has changed to status: ' . $this->order->status)
                    ->line('You can enter the application for more details')
                    ->action('Login', route('login'))
                    ->line('Thank you!');
    }
}
