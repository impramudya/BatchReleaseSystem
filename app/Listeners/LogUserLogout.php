<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    public function handle(Logout $event): void
    {

        if (! $event->user) {
            return;
        }

        LoginLog::create([
            'user_id' => $event->user->id,
            'action' => 'logout',
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
