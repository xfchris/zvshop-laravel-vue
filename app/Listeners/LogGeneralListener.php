<?php

namespace App\Listeners;

use App\Events\LogGeneralEvent;
use Illuminate\Support\Facades\Log;

class LogGeneralListener
{
    public function handle(LogGeneralEvent $event): void
    {
        Log::log($event->level, $event->message);
    }
}
