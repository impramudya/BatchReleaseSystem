<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}?v=4">
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
                <h1>Forgot Password?</h1>
                <p>Enter your registered email address and we will send you a link to reset your password.</p>
            </div>

            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="field">
                    <label for="email">Corporate Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="name@company.com">
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit">Send Reset Link</button>
            </form>

        </div>

        <div class="back-link">
            <a href="{{ route('login') }}">&larr; Back to Sign In</a>
        </div>

    </div>

</body>
</html>
