<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BatchReleasePro</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FAFAF8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            margin: 0;
        }

        .wrapper {
            width: 100%;
            max-width: 384px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background-color: #171717;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid rgba(229, 229, 229, 0.7);
            border-radius: 16px;
            padding: 36px 32px;
        }

        .card-header {
            margin-bottom: 28px;
        }

        .card-header h1 {
            font-size: 1.35rem;
            font-weight: 600;
            color: #171717;
            letter-spacing: -0.01em;
            margin: 0;
        }

        .card-header p {
            font-size: 0.875rem;
            color: #737373;
            margin-top: 6px;
            line-height: 1.6;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: #525252;
            margin-bottom: 6px;
        }

        .field input {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: #171717;
            outline: none;
            transition: all 0.15s ease;
        }

        .field input::placeholder {
            color: #a3a3a3;
        }

        .field input:focus {
            border-color: #a3a3a3;
            box-shadow: 0 0 0 3px rgba(23, 23, 23, 0.1);
        }

        .field .error {
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 6px;
        }

        button[type="submit"] {
            width: 100%;
            background-color: #171717;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-top: 4px;
        }

        button[type="submit"]:hover {
            background-color: #262626;
        }

        button[type="submit"]:active {
            transform: scale(0.99);
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
        }

        .back-link a {
            font-size: 0.875rem;
            color: #737373;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .back-link a:hover {
            color: #262626;
        }
    </style>
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
