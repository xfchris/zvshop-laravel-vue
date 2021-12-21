<?php

namespace App\Listeners;

use App\Events\BanUnbanUserEvent;
use App\Notifications\SendBanUnbanNotification;

class SendEmailBanUnbanListener
{
    public function handle(BanUnbanUserEvent $event): void
    {
        $event->user->notify(new SendBanUnbanNotification($event->user));
    }
}
