<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ExportCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyOfCompletedExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $title,
        public string $url,
    ) {
    }

    public function handle(): void
    {
        $this->user->notify(new ExportCompletedNotification($this->user, $this->title, $this->url));
        Log::log('info', 'An export has been created by the user: Name=' . $this->user->name . ' id=' . $this->user->id);
    }
}
