<?php

namespace App\Services\Trait;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait NotifyLog
{
    private function notifyLog($element, $id, $action)
    {
        return Log::info($element . ': ' . $id . ' has been ' . $action . ' by: ' . Auth::user()->name . ', id: ' . Auth::user()->id);
    }
}
