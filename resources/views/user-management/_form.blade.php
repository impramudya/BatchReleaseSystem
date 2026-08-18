{{-- Dipakai oleh create.blade.php dan edit.blade.php --}}
<div class="um-field-row">
    <div class="um-field">
        <label for="name">Nama Lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required autofocus>
        @error('name') <p class="um-error">{{ $message }}</p> @enderror
    </div>

    <div class="um-field">
        <label for="role">Role</label>
        <select id="role" name="role" required>
            @php $selectedRole = old('role', $user->role ?? ''); @endphp
            <option value="" disabled {{ $selectedRole === '' ? 'selected' : '' }}>Pilih role</option>
            @foreach (['Admin', 'Super Admin', 'Supervisor', 'Manager', 'Viewer'] as $roleOption)
                <option value="{{ $roleOption }}" {{ $selectedRole === $roleOption ? 'selected' : '' }}>{{ $roleOption }}</option>
            @endforeach
        </select>
        @error('role') <p class="um-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="um-field">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email') <p class="um-error">{{ $message }}</p> @enderror
</div>

<div class="um-field-row">
    <div class="um-field">
        <label for="password">{{ isset($user) ? 'Password Baru' : 'Password' }}</label>
        <input id="password" type="password" name="password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
        @if (isset($user))
            <span class="hint">Kosongkan jika tidak ingin mengubah password.</span>
        @endif
        @error('password') <p class="um-error">{{ $message }}</p> @enderror
    </div>

    <div class="um-field">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
    </div>
</div>

<div class="um-field">
    <label for="status">Status Akun</label>
    <select id="status" name="status">
        @php $selectedStatus = old('status', $user->status ?? 'active'); @endphp
        <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
    </select>
</div>
