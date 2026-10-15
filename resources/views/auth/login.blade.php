<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BatchReleasePro | QA Batch Release System</title>
    
    <!-- Core Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v=5">
</head>
<body class="brp-body">

    <div class="brp-auth-shell">

        <!-- ========================================================= -->
        <!-- LEFT SIDE: Enterprise Brand Showcase (Corporate Green)    -->
        <!-- ========================================================= -->
        <div class="brp-brand-side">
            
            <!-- Regulatory Compliance Header -->
            <div class="brp-brand-header">
                <div class="brp-compliance-badge">
                    <svg class="brp-badge-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                    </svg>
                    <span>cGMP Validated &bull; 21 CFR Part 11</span>
                </div>
            </div>

            <!-- Brand Identity & Description -->
            <div class="brp-brand-identity">
                <div class="brp-logo-group">
                    <div class="brp-emblem-wrap">
                        <!-- Quality Shield & Verification Emblem -->
                        <svg class="brp-emblem-icon" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 2L4 6.5V14C4 21.5 9.1 28.5 16 30C22.9 28.5 28 21.5 28 14V6.5L16 2Z" fill="currentColor" fill-opacity="0.12"/>
                            <path d="M16 2L4 6.5V14C4 21.5 9.1 28.5 16 30C22.9 28.5 28 21.5 28 14V6.5L16 2Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11 15.5L14.5 19L21.5 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="brp-brand-name">BatchRelease<span class="pro-badge">PRO</span></h1>
                    </div>
                </div>

                <h2 class="brp-system-title">Pharmaceutical Batch Release &amp; Quality Assurance System</h2>
                <p class="brp-system-desc">
                    Integrated batch integrity verification and quality compliance platform for Inhouse and Toll Out operations. Validating critical quality attributes prior to market distribution.
                </p>

                <!-- Core Assurance Pillars -->
                <div class="brp-pillars">
                    <div class="brp-pillar-item">
                        <svg class="brp-pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="brp-pillar-text">
                            <strong>Electronic Batch Record &amp; Review</strong>
                            <p>Standardized automated checklist evaluations across Stripping, Bottling, and Toll Out lines.</p>
                        </div>
                    </div>

                    <div class="brp-pillar-item">
                        <svg class="brp-pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <div class="brp-pillar-text">
                            <strong>Dual Approval Sign-off Workflow</strong>
                            <p>Structured review and certified dual sign-off by QC Supervisor and QA Manager.</p>
                        </div>
                    </div>

                    <div class="brp-pillar-item">
                        <svg class="brp-pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="brp-pillar-text">
                            <strong>Tamper-Evident Audit Trail</strong>
                            <p>Secure, unalterable digital activity logging compliant with 21 CFR Part 11 standards.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brand Footer / Connection Status -->
            <div class="brp-brand-footer">
                <div class="brp-live-status">
                    <span class="brp-status-dot" aria-hidden="true"></span>
                    <span>System Connected &bull; QA Active Node</span>
                </div>
                <span>Version 1.2 Enterprise</span>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- RIGHT SIDE: Corporate User Authentication Form (White)    -->
        <!-- ========================================================= -->
        <div class="brp-form-side">

            <div class="brp-form-card">

                <!-- Mobile Brand Header -->
                <div class="brp-mobile-brand">
                    <div class="brp-mobile-emblem">
                        <svg width="24" height="24" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M16 2L4 6.5V14C4 21.5 9.1 28.5 16 30C22.9 28.5 28 21.5 28 14V6.5L16 2Z"/>
                            <path d="M11 15.5L14.5 19L21.5 12"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="brp-mobile-title">BatchRelease<span>PRO</span></h2>
                    </div>
                </div>

                <!-- Form Header -->
                <div class="brp-form-header">
                    <div class="brp-pharma-badge-pill">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h2v2h-2v-2zm0-10h2v8h-2V6z"/>
                        </svg>
                        Internal QA / QC Portal
                    </div>
                    <h1 class="brp-form-title">Sign In to Your Account</h1>
                    <p class="brp-form-subtitle">Enter your registered company credentials to access the system.</p>
                </div>

                <!-- Status Flash Message -->
                @if (session('status'))
                    <div class="brp-alert brp-alert-success" role="alert">
                        <svg class="brp-alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="brp-form" novalidate>
                    @csrf

                    <!-- Field: Email -->
                    <div class="brp-field-group">
                        <label for="email" class="brp-label">
                            <span>Corporate Email Address <span class="brp-label-req">*</span></span>
                        </label>
                        <div class="brp-input-wrapper">
                            <svg class="brp-input-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A1.5 1.5 0 014.5 4h11A1.5 1.5 0 0117 5.5v9a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 14.5v-9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5.5L10 10.5l6.5-5"/>
                            </svg>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                class="brp-input @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                placeholder="name@company.com"
                                required 
                                autofocus 
                                autocomplete="username"
                                aria-describedby="@error('email') email-error @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="brp-field-error" id="email-error">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Field: Password -->
                    <div class="brp-field-group">
                        <label for="password" class="brp-label">
                            <span>Password <span class="brp-label-req">*</span></span>
                        </label>
                        <div class="brp-input-wrapper">
                            <svg class="brp-input-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                <rect x="3.5" y="8" width="13" height="10" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 8V5.5a3.5 3.5 0 117 0V8"/>
                            </svg>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                class="brp-input brp-input-password @error('password') is-invalid @enderror" 
                                placeholder="Enter your password"
                                required 
                                autocomplete="current-password"
                                aria-describedby="@error('password') password-error @enderror"
                            >
                            <button 
                                type="button" 
                                class="brp-password-toggle" 
                                id="password-toggle" 
                                aria-label="Show password" 
                                aria-pressed="false"
                            >
                                <svg class="brp-toggle-icon icon-eye" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 10S4.5 4 10 4s8.5 6 8.5 6-3 6-8.5 6-8.5-6-8.5-6z"/>
                                    <circle cx="10" cy="10" r="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg class="brp-toggle-icon icon-eye-off" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 2.5l15 15"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.3 4.3A8.6 8.6 0 0110 4c5.5 0 8.5 6 8.5 6a15.6 15.6 0 01-2.7 3.6M5.9 5.9C3.2 7.5 1.5 10 1.5 10s3 6 8.5 6c1.1 0 2.1-.2 3-.6"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.1 8.1a2.3 2.3 0 003.2 3.3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="brp-field-error" id="password-error">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Row: Remember Me & Forgot Password -->
                    <div class="brp-form-utility">
                        <label class="brp-checkbox-label">
                            <input type="checkbox" name="remember" class="brp-checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="brp-link-forgot">Forgot Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="brp-btn-submit">
                        <span>Sign In</span>
                        <svg class="brp-btn-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m0 0l-4-4m4 4l-4 4"/>
                        </svg>
                    </button>
                </form>

                <!-- Security & Regulatory Notice -->
                <div class="brp-security-notice">
                    <svg class="brp-security-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong>Authorized QA/QC Personnel Only</strong>
                        This is a restricted enterprise system. All authentication attempts and activities are monitored and recorded in the audit trail for cGMP regulatory compliance.
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Toggle Password Script -->
    <script src="{{ asset('js/login.js') }}?v=5" defer></script>

</body>
</html>
