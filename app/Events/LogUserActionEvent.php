<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class LogUserActionEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public string $channel,
        public string $element,
        public string|int $id,
        public string $action,
        public string $level = 'info',
        public array $user = []
    ) {
        $this->user = Auth::user()->toArray();
    }
}
