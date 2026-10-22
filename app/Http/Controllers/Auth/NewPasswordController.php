<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class NewPasswordController extends Controller
{
    public function create($token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                if (Hash::check($request->password, $user->password)) {
                    abort(422, 'New password cannot be the same as your current password.');
                }

                foreach ($user->passwordHistories()->latest()->take(5)->get() as $old) {
                    if (Hash::check($request->password, $old->password)) {
                        abort(422, 'New password cannot be the same as any of your recently used passwords.');
                    }
                }

                PasswordHistory::create([
                    'user_id' => $user->id,
                    'password' => $user->password,
                ]);

                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'password_changed_at' => now(),
                    'failed_login_attempts' => 0,
                    'locked_at' => null,
                    'status' => 'active',
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
