<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/change-password.css') }}">
</head>
<body>

    <div class="wrapper">

        <div class="logo-container">
            <div class="logo">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
        </div>

        <div class="card">

            <div class="card-header">
                <h1>Ganti password</h1>
                <p>Pastikan password baru kamu kuat dan belum pernah dipakai sebelumnya.</p>
            </div>

            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}">
                @csrf

                <div class="field">
                    <label for="current_password">Password lama</label>
                    <input id="current_password" type="password" name="current_password" required
                        placeholder="••••••••">
                    @error('current_password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password baru</label>
                    <input id="password" type="password" name="password" required
                        placeholder="••••••••">
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi password baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        placeholder="••••••••">
                </div>

                <button type="submit">Simpan password baru</button>
            </form>

        </div>

    </div>

</body>
</html>
