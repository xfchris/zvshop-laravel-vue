<?php

namespace App\Listeners;

use App\Events\LogGeneralEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogGeneralListener implements ShouldQueue
{
    public function handle(LogGeneralEvent $event): void
    {
        Log::log($event->level, $event->message);
    }
}
