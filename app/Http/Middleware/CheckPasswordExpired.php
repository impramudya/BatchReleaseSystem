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
            $changedAt = $user->password_changed_at ?? $user->created_at;

            if (now()->diffInDays($changedAt) >= 90) {
                return redirect()->route('password.change')
                    ->with('warning', 'Password Anda sudah lebih dari 90 hari, silakan ganti password terlebih dahulu.');
            }
        }

        return $next($request);
    }
}