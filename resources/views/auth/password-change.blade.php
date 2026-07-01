<x-layouts.auth>
    @if (session('warning'))
        <div class="text-red-600 mb-4">{{ session('warning') }}</div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <div>
            <label>Password Lama</label>
            <input type="password" name="current_password" required>
        </div>

        <div>
            <label>Password Baru</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required>
        </div>

        @error('password')
            <p class="text-red-600">{{ $message }}</p>
        @enderror
        @error('current_password')
            <p class="text-red-600">{{ $message }}</p>
        @enderror

        <button type="submit">Ganti Password</button>
    </form>
</x-layouts.auth>