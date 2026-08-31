<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        LoginLog::create([
            'user_id' => $event->user->id,
            'action' => 'login',
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
