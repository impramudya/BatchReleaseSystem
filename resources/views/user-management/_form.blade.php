{{-- Dipakai oleh create.blade.php dan edit.blade.php --}}
<div class="um-field-row">
    <div class="um-field">
        <label for="name">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required autofocus>
        @error('name') <p class="um-error">{{ $message }}</p> @enderror
    </div>

    <div class="um-field">
        <label for="role">Role</label>
        <select id="role" name="role" required>
            @php $selectedRole = old('role', $user->role ?? ''); @endphp
            <option value="" disabled {{ $selectedRole === '' ? 'selected' : '' }}>Select Role</option>
            @foreach (['Admin', 'System Administrator', 'Supervisor', 'Manager'] as $roleOption)
            <option value="{{ $roleOption }}" {{ $selectedRole === $roleOption ? 'selected' : '' }}>{{ $roleOption }}</option>
            @endforeach
        </select>
        @error('role') <p class="um-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="um-field-row">
    <div class="um-field">
        <label for="nik">NIK</label>
        <input id="nik" type="text" name="nik" value="{{ old('nik', $user->nik ?? '') }}" placeholder="E.g., 1001">
        @error('nik') <p class="um-error">{{ $message }}</p> @enderror
    </div>

    <div class="um-field">
        <label for="initial">Initial</label>
        <input id="initial" type="text" name="initial" value="{{ old('initial', $user->initial ?? '') }}" placeholder="E.g., BFY" maxlength="10" style="text-transform: uppercase;">
        @error('initial') <p class="um-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="um-field">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email') <p class="um-error">{{ $message }}</p> @enderror
</div>

<div class="um-field-row">
    <div class="um-field">
        <label for="password">{{ isset($user) ? 'New Password' : 'Password' }}</label>
        <input id="password" type="password" name="password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
        <span class="hint" style="display:block; margin-top:4px; font-size:0.75rem; color:var(--text-secondary);">
            Must be at least 8 characters with uppercase, lowercase, numbers, and symbols.
            {{ isset($user) ? 'Leave blank to keep current password.' : '' }}
        </span>
        @error('password') <p class="um-error">{{ $message }}</p> @enderror
    </div>

    <div class="um-field">
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
    </div>
</div>

<div class="um-field">
    <label for="status">Account Status</label>
    <select id="status" name="status">
        @php $selectedStatus = old('status', $user->status ?? 'active'); @endphp
        <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
