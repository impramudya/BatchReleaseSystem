<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();

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
            'nik'      => ['nullable', 'string', 'max:50'],
            'initial'  => ['nullable', 'string', 'max:20'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'string', 'max:100'],
            'status'   => ['nullable', 'in:active,inactive'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $newUser = User::create([
            'name'     => $validated['name'],
            'nik'      => $validated['nik'] ?? null,
            'initial'  => !empty($validated['initial']) ? strtoupper($validated['initial']) : null,
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'status'   => $validated['status'] ?? 'active',
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'create_user',
            'action_label' => 'Add User',
            'target_type'  => 'User',
            'target_id'    => $newUser->id,
            'details'      => "Created user \"{$newUser->name}\" (Email: {$newUser->email}, Role: {$newUser->role}, NIK: " . ($newUser->nik ?: '—') . ", Initial: " . ($newUser->initial ?: '—') . ", Status: {$newUser->status})",
            'ip_address'   => $request->ip(),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', 'New user successfully added.');
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
            'nik'      => ['nullable', 'string', 'max:50'],
            'initial'  => ['nullable', 'string', 'max:20'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', 'string', 'max:100'],
            'status'   => ['nullable', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
        ]);

        $old = [
            'name'    => $user->name,
            'nik'     => $user->nik,
            'initial' => $user->initial,
            'email'   => $user->email,
            'role'    => $user->role,
            'status'  => $user->status,
        ];

        $user->name    = $validated['name'];
        $user->nik     = $validated['nik'] ?? null;
        $user->initial = !empty($validated['initial']) ? strtoupper($validated['initial']) : null;
        $user->email   = $validated['email'];
        $user->role    = $validated['role'];
        $user->status  = $validated['status'] ?? $user->status;

        // If reactivating or keeping active, clear lockout
        if ($user->status === 'active') {
            $user->failed_login_attempts = 0;
            $user->locked_at = null;
        }

        $passwordChanged = false;
        if (!empty($validated['password'])) {
            if (Hash::check($validated['password'], $user->password)) {
                return back()->withInput()->withErrors([
                    'password' => 'New password cannot be the same as current password.',
                ]);
            }

            foreach ($user->passwordHistories()->latest()->take(5)->get() as $oldHistory) {
                if (Hash::check($validated['password'], $oldHistory->password)) {
                    return back()->withInput()->withErrors([
                        'password' => 'New password cannot be the same as any of the user\'s recently used passwords.',
                    ]);
                }
            }

            PasswordHistory::create([
                'user_id' => $user->id,
                'password' => $user->password,
            ]);

            $user->password = Hash::make($validated['password']);
            $user->password_changed_at = now();
            $passwordChanged = true;
        }

        $user->save();

        // Track field changes for audit trail
        $changes = [];
        if ($old['name'] !== $user->name) {
            $changes[] = "Name changed from \"{$old['name']}\" to \"{$user->name}\"";
        }
        if ($old['nik'] !== $user->nik) {
            $from = $old['nik'] ?: '—';
            $to = $user->nik ?: '—';
            $changes[] = "NIK changed from \"{$from}\" to \"{$to}\"";
        }
        if ($old['initial'] !== $user->initial) {
            $from = $old['initial'] ?: '—';
            $to = $user->initial ?: '—';
            $changes[] = "Initial changed from \"{$from}\" to \"{$to}\"";
        }
        if ($old['email'] !== $user->email) {
            $changes[] = "Email changed from \"{$old['email']}\" to \"{$user->email}\"";
        }
        if ($old['role'] !== $user->role) {
            $changes[] = "Role changed from \"{$old['role']}\" to \"{$user->role}\"";
        }
        if ($old['status'] !== $user->status) {
            $changes[] = "Status changed from \"{$old['status']}\" to \"{$user->status}\"";
        }
        if ($passwordChanged) {
            $changes[] = "Password updated";
        }

        $detailStr = !empty($changes) ? implode('; ', $changes) : 'No attributes modified';

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'edit_user',
            'action_label' => 'Edit User',
            'target_type'  => 'User',
            'target_id'    => $user->id,
            'details'      => "Updated user \"{$old['name']}\": {$detailStr}",
            'ip_address'   => $request->ip(),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User changes successfully saved.');
    }

    /**
     * Unlock locked user.
     */
    public function unlock(User $user)
    {
        $user->unlockAccount();

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'unlock_user',
            'action_label' => 'Unlock User',
            'target_type'  => 'User',
            'target_id'    => $user->id,
            'details'      => "Unlocked account for user \"{$user->name}\" (Restored to Active status)",
            'ip_address'   => request()->ip(),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', "Account for {$user->name} has been successfully unlocked.");
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('user-management.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $deletedName = $user->name;
        $deletedEmail = $user->email;
        $deletedRole = $user->role;
        $deletedId = $user->id;

        $user->delete();

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'delete_user',
            'action_label' => 'Delete User',
            'target_type'  => 'User',
            'target_id'    => $deletedId,
            'details'      => "Deleted user \"{$deletedName}\" (Email: {$deletedEmail}, Role: {$deletedRole})",
            'ip_address'   => request()->ip(),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User successfully deleted.');
    }

    public function resetPassword(User $user)
    {
        $temporaryPassword = 'Password@123';

        $user->update([
            'password' => Hash::make($temporaryPassword),
            'password_changed_at' => null,
            'failed_login_attempts' => 0,
            'locked_at' => null,
            'status' => 'active',
        ]);

        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'reset_password',
            'action_label' => 'Reset Password',
            'target_type'  => 'User',
            'target_id'    => $user->id,
            'details'      => "Reset password for user \"{$user->name}\" to temporary password",
            'ip_address'   => request()->ip(),
        ]);

        return redirect()
            ->route('user-management.index')
            ->with('status', "Password for {$user->name} successfully reset to: {$temporaryPassword}");
    }
}
