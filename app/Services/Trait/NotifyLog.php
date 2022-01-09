<?php

namespace App\Services\Trait;

use App\Events\LogUserActionEvent;

trait NotifyLog
{
    private function notifyLog($channel, $element, $id, $action)
    {
        LogUserActionEvent::dispatch(['channel' => $channel, 'element' => $element, 'id' => $id, 'action' => $action]);
    }
}
