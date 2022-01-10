<?php

namespace App\Listeners;

use App\Events\LogUserActionEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActionListener
{
    public function handle(LogUserActionEvent $e): void
    {
        $message = $e->element . ': ' . $e->id . ' has been ' . $e->action . ' by: ' . Auth::user()->name . ', id: ' . Auth::user()->id;

        Log::channel($e->channel)->log($e->level, $message);
    }
}
