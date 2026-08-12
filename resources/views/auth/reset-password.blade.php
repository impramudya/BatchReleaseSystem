<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
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
                <h1>Atur ulang password</h1>
                <p>Buat password baru untuk akun kamu. Pastikan mudah diingat, tapi tetap aman.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Alamat email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                        placeholder="nama@perusahaan.com">
                    @error('email')
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
                    <label for="password_confirmation">Konfirmasi password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        placeholder="••••••••">
                </div>

                <button type="submit">Reset password</button>
            </form>

        </div>

        <div class="back-link">
            <a href="{{ route('login') }}">← Kembali ke halaman login</a>
        </div>

    </div>

</body>
</html>
