<?php

namespace App\Listeners;

use App\Events\LogUserActionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogUserActionListener implements ShouldQueue
{
    public function handle(LogUserActionEvent $e): void
    {
        $message = $e->element . ': ' . $e->id . ' has been ' . $e->action . ' by: ' . $e->user['name'] . ', id: ' . $e->user['id'];

        Log::channel($e->channel)->log($e->level, $message);
    }
}
