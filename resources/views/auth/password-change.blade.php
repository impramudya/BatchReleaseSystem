<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($isFirstLogin ?? is_null(auth()->user()->password_changed_at)) ? 'Set Your Password' : 'Change Password' }} - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/change-password.css') }}?v={{ time() }}">
</head>
<body>

    @php
        $isFirst = $isFirstLogin ?? is_null(auth()->user()->password_changed_at);
    @endphp

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
                <h1>{{ $isFirst ? 'Set New Password' : 'Change Password' }}</h1>
                <p>{{ $isFirst ? 'This is your first login. Please create a strong personal password to activate your account.' : 'Ensure your new password is strong and has not been used previously.' }}</p>
            </div>

            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert-warning">
                    {{ session('warning') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}" id="password-form">
                @csrf

                @if (! $isFirst)
                <div class="field">
                    <label for="current_password">Current Password</label>
                    <input id="current_password" type="password" name="current_password" required
                        placeholder="••••••••">
                    @error('current_password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                @endif

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

                <button type="submit" id="submit-btn">{{ $isFirst ? 'Activate & Continue' : 'Update Password' }}</button>
            </form>

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

                // Rule 1: Length >= 8
                const hasLength = val.length >= 8;
                ruleLength.classList.toggle('valid', hasLength);

                // Rule 2: Uppercase letter
                const hasUpper = /[A-Z]/.test(val);
                ruleUpper.classList.toggle('valid', hasUpper);

                // Rule 3: Lowercase letter
                const hasLower = /[a-z]/.test(val);
                ruleLower.classList.toggle('valid', hasLower);

                // Rule 4: Number
                const hasNumber = /[0-9]/.test(val);
                ruleNumber.classList.toggle('valid', hasNumber);

                // Rule 5: Symbol / non-alphanumeric
                const hasSymbol = /[^A-Za-z0-9]/.test(val);
                ruleSymbol.classList.toggle('valid', hasSymbol);

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

            // Initial check if values are autofilled
            if (pwdInput.value) {
                checkRules();
            }
        });
    </script>

</body>
</html>
