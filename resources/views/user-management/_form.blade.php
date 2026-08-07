{{-- Dipakai oleh create.blade.php dan edit.blade.php --}}

<style>
    .um-form-card {
    background: var(--content-surface);
    border: 1px solid var(--content-border);
    border-radius: 8px;
    width: 100%;
    overflow: hidden;
}

    .um-form-head {
        padding: 1.15rem 1.4rem;
        border-bottom: 1px solid var(--content-border);
    }
    .um-form-title {
        font-family: 'poppins', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: var(--content-text);
    }
    .um-form-sub {
        font-family: 'poppins', sans-serif;
        font-size: 0.72rem;
        color: var(--content-text-soft);
        margin: 0.25rem 0 0;
    }

    .um-form-body { padding: 1.4rem; }

    .um-field { margin-bottom: 1.1rem; }
    .um-field:last-child { margin-bottom: 0; }

    .um-field label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--content-text);
        margin-bottom: 0.4rem;
    }

    .um-field .hint {
        display: block;
        font-size: 0.75rem;
        font-weight: 400;
        color: var(--content-text-soft);
        margin-top: 0.3rem;
    }

    .um-field input[type="text"],
    .um-field input[type="email"],
    .um-field input[type="password"],
    .um-field select {
        width: 100%;
        padding: 0.62rem 0.75rem;
        font-size: 0.9rem;
        font-family: 'poppins', sans-serif;
        color: var(--content-text);
        background: var(--content-bg);
        border: 1px solid var(--content-border);
        border-radius: 5px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .um-field input:focus-visible,
    .um-field select:focus-visible {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.15);
    }

    .um-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 560px) { .um-field-row { grid-template-columns: 1fr; } }

    .um-error { font-size: 0.78rem; color: var(--danger); margin: 0.35rem 0 0; }

    .um-form-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 1.4rem;
        border-top: 1px solid var(--content-border);
        background: var(--content-bg);
    }

    .um-btn-primary {
        padding: 0.62rem 1.1rem;
        font-size: 0.88rem;
        font-weight: 600;
        color: #fff;
        background: var(--teal);
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .um-btn-primary:hover { background: var(--teal-dark); }

    .um-btn-ghost {
        padding: 0.62rem 1.1rem;
        font-size: 0.88rem;
        color: var(--content-text-soft);
        background: transparent;
        border: 1px solid var(--content-border);
        border-radius: 5px;
        text-decoration: none;
    }
    .um-btn-ghost:hover { color: var(--content-text); border-color: var(--teal); }
</style>

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
            @foreach (['Admin', 'QA Approver', 'QA Reviewer', 'Operator', 'Viewer'] as $roleOption)
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
