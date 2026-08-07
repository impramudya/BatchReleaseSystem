<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('user-management.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        return view('user-management.create');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'string', 'max:100'],
            'status'   => ['nullable', 'in:active,inactive'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'status'   => $validated['status'] ?? 'active',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('user-management.edit', compact('user'));
    }

    /**
     * Simpan perubahan user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', 'string', 'max:100'],
            'status'   => ['nullable', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name   = $validated['name'];
        $user->email  = $validated['email'];
        $user->role   = $validated['role'];
        $user->status = $validated['status'] ?? $user->status;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('user-management.index')
            ->with('status', 'Perubahan user berhasil disimpan.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
    return redirect()
        ->route('user-management.index')
        ->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
}

        $user->delete();

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User berhasil dihapus.');
    }

    public function resetPassword(User $user)
    {
        $temporaryPassword = str()->random(10);

        $user->update([
            'password' => Hash::make($temporaryPassword),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', "Password {$user->name} berhasil direset.");
    }
}
