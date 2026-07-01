<?php

namespace App\Http\Controllers;

use App\Models\PasswordHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function edit()
    {
        return view('auth.password-change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();

        // Cek terhadap password saat ini
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password baru tidak boleh sama dengan password sebelumnya.',
            ]);
        }

        // Cek terhadap history (5 password terakhir)
        foreach ($user->passwordHistories as $old) {
            if (Hash::check($request->password, $old->password)) {
                return back()->withErrors([
                    'password' => 'Password tidak boleh sama dengan password yang pernah dipakai sebelumnya.',
                ]);
            }
        }

        // Simpan password lama ke history
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui.');
    }
}