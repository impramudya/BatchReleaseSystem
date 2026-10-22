<?php

namespace App\Http\Controllers;

use App\Models\PasswordHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function edit()
    {
        $isFirstLogin = is_null(auth()->user()->password_changed_at);
        return view('auth.password-change', compact('isFirstLogin'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $isFirstLogin = is_null($user->password_changed_at);

        $rules = [
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if (! $isFirstLogin) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $request->validate($rules);

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => $isFirstLogin
                    ? 'New password cannot be the same as your temporary password.'
                    : 'New password cannot be the same as your current password.',
            ]);
        }

        foreach ($user->passwordHistories()->latest()->take(5)->get() as $old) {
            if (Hash::check($request->password, $old->password)) {
                return back()->withErrors([
                    'password' => 'New password cannot be the same as any of your recently used passwords.',
                ]);
            }
        }

        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'failed_login_attempts' => 0,
            'locked_at' => null,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'Your password has been successfully updated.');
    }
}