<?php

namespace App\Listeners;

use App\Events\LogUserActionEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActionListener
{
    public function handle(LogUserActionEvent $event): void
    {
        $data = $event->logData;
        Log::channel($data['channel'])->log(
            $data['level'] ?? 'info',
            $data['element'] . ': ' . $data['id'] . ' has been ' . $data['action'] . ' by: ' . Auth::user()->name . ', id: ' . Auth::user()->id
        );
    }
}
