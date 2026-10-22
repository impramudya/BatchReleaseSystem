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
            $isFirstLogin = is_null($changedAt);
            $isExpired = $changedAt && now()->diffInDays($changedAt) >= 60;

            if ($isFirstLogin || $isExpired) {
                if ($request->routeIs('dashboard')) {
                    session()->flash('force_password_change', true);
                    session()->flash('password_change_reason', $isFirstLogin ? 'first_login' : 'expired');
                } else {
                    return redirect()->route('password.change')->with(
                        'warning',
                        $isFirstLogin
                            ? 'This is your first login. For security compliance, please set a new password.'
                            : 'Your password has expired (older than 60 days). Please update your password to continue.'
                    );
                }
            }
        }

        return $next($request);
    }
}
