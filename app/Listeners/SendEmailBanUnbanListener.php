<?php

namespace App\Listeners;

use App\Events\BanUnbanUserEvent;
use App\Notifications\SendBanUnbanNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailBanUnbanListener implements ShouldQueue
{
    public function handle(BanUnbanUserEvent $event): void
    {
        $event->user->notify(new SendBanUnbanNotification($event->user));
    }
}
