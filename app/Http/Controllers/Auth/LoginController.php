<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->isLocked()) {
            throw ValidationException::withMessages([
                'email' => __('Your account has been locked due to consecutive failed login attempts or administrator action. Please contact your Administrator.'),
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($user) {
                $user->increment('failed_login_attempts');
                $attempts = $user->failed_login_attempts;

                if ($attempts >= 5) {
                    $user->lockAccount();
                    throw ValidationException::withMessages([
                        'email' => __('Your account has been locked after 5 consecutive failed login attempts. Please contact your Administrator.'),
                    ]);
                }

                $remaining = 5 - $attempts;
                throw ValidationException::withMessages([
                    'email' => __("Invalid credentials. You have {$remaining} attempt(s) remaining before your account is locked."),
                ]);
            }

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        $request->session()->regenerate();

        $user->update([
            'failed_login_attempts' => 0,
            'locked_at' => null,
            'last_login' => now(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
