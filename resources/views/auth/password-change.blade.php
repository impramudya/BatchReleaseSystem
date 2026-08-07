<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - BatchReleasePro</title>
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

        .alert-success {
            margin-bottom: 20px;
            font-size: 0.875rem;
            color: #047857;
            background-color: #ecfdf5;
            border: 1px solid #d1fae5;
            padding: 10px 14px;
            border-radius: 8px;
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
