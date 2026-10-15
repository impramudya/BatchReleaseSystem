<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}?v=4">
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
                <h1>Reset Password</h1>
                <p>Create a new password for your account. Ensure it is secure and meets company policy.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Corporate Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                        placeholder="name@company.com">
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">New Password</label>
                    <input id="password" type="password" name="password" required
                        placeholder="••••••••">
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        placeholder="••••••••">
                </div>

                <button type="submit">Reset Password</button>
            </form>

        </div>

        <div class="back-link">
            <a href="{{ route('login') }}">&larr; Back to Sign In</a>
        </div>

    </div>

</body>
</html>
