<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}?v={{ time() }}">
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
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        placeholder="••••••••">

                    <div class="password-policy-checklist" id="policy-checklist">
                        <span class="policy-title">Password Requirements:</span>
                        <ul class="policy-list">
                            <li id="rule-length" class="policy-item">
                                <span class="policy-icon">
                                    <svg viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="policy-label">At least 8 characters</span>
                            </li>
                            <li id="rule-uppercase" class="policy-item">
                                <span class="policy-icon">
                                    <svg viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="policy-label">At least one uppercase letter (A–Z)</span>
                            </li>
                            <li id="rule-lowercase" class="policy-item">
                                <span class="policy-icon">
                                    <svg viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="policy-label">At least one lowercase letter (a–z)</span>
                            </li>
                            <li id="rule-number" class="policy-item">
                                <span class="policy-icon">
                                    <svg viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="policy-label">At least one number (0–9)</span>
                            </li>
                            <li id="rule-symbol" class="policy-item">
                                <span class="policy-icon">
                                    <svg viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="policy-label">At least one special character (!@#$%^&*...)</span>
                            </li>
                        </ul>
                    </div>

                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="••••••••">
                    <div id="match-indicator" class="password-match-indicator" style="display: none;"></div>
                </div>

                <button type="submit">Reset Password</button>
            </form>

        </div>

        <div class="back-link">
            <a href="{{ route('login') }}">&larr; Back to Sign In</a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwdInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const matchIndicator = document.getElementById('match-indicator');

            const ruleLength = document.getElementById('rule-length');
            const ruleUpper = document.getElementById('rule-uppercase');
            const ruleLower = document.getElementById('rule-lowercase');
            const ruleNumber = document.getElementById('rule-number');
            const ruleSymbol = document.getElementById('rule-symbol');

            function checkRules() {
                const val = pwdInput.value || '';

                ruleLength.classList.toggle('valid', val.length >= 8);
                ruleUpper.classList.toggle('valid', /[A-Z]/.test(val));
                ruleLower.classList.toggle('valid', /[a-z]/.test(val));
                ruleNumber.classList.toggle('valid', /[0-9]/.test(val));
                ruleSymbol.classList.toggle('valid', /[^A-Za-z0-9]/.test(val));

                checkMatch();
            }

            function checkMatch() {
                const pwdVal = pwdInput.value || '';
                const confirmVal = confirmInput.value || '';

                if (!confirmVal) {
                    matchIndicator.style.display = 'none';
                    return;
                }

                matchIndicator.style.display = 'flex';
                if (pwdVal === confirmVal) {
                    matchIndicator.className = 'password-match-indicator matched';
                    matchIndicator.innerHTML = '<svg viewBox="0 0 16 16" fill="currentColor" width="13" height="13"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg> Passwords match';
                } else {
                    matchIndicator.className = 'password-match-indicator unmatched';
                    matchIndicator.innerHTML = '<svg viewBox="0 0 16 16" fill="currentColor" width="13" height="13"><path d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z"/></svg> Passwords do not match';
                }
            }

            pwdInput.addEventListener('input', checkRules);
            confirmInput.addEventListener('input', checkMatch);

            if (pwdInput.value) checkRules();
        });
    </script>

</body>
</html>
