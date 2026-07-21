<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPasswordExpired
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $request->routeIs('password.change', 'password.change.update', 'logout')) {
            $changedAt = $user->password_changed_at;

            if (is_null($changedAt)) {
                session()->flash('force_password_change', true);
                session()->flash('password_change_reason', 'first_login');
            } elseif (now()->diffInDays($changedAt) >= 90) {
                session()->flash('force_password_change', true);
                session()->flash('password_change_reason', 'expired');
            }
        }

        return $next($request);
    }
}
