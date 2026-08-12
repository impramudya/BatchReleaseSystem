<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BatchReleasePro</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=3">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v=3">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="brp-body">

    <div class="brp-shell">

        <!-- Identity panel -->
        <div class="brp-panel">
            <div>
                <div class="brp-eyebrow">Sistem Rilis Batch &mdash; QA</div>
                <h2 class="brp-brand">BatchReleasePro</h2>
                <p class="brp-tagline">Setiap batch diverifikasi dan dicatat sebelum dilepas ke jalur berikutnya.</p>
            </div>

            <div class="brp-seal-wrap">
                <svg class="brp-seal" viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                        <path id="brp-ring" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0" />
                    </defs>
                    <circle cx="100" cy="100" r="92" fill="none" stroke="rgba(247,245,240,0.14)" stroke-width="1"/>
                    <circle cx="100" cy="100" r="78" fill="none" stroke="#c08a28" stroke-width="1.2"/>
                    <circle cx="100" cy="100" r="58" fill="none" stroke="rgba(247,245,240,0.2)" stroke-width="1"/>
                    <text font-family="IBM Plex Mono, monospace" font-size="10.5" letter-spacing="3" fill="#c08a28">
                        <textPath href="#brp-ring" startOffset="0%">VERIFIED &#8226; QA APPROVED &#8226; RELEASED &#8226;</textPath>
                    </text>
                    <text x="100" y="96" text-anchor="middle" font-family="Space Grotesk, sans-serif" font-size="15" font-weight="700" fill="#f7f5f0">BRP</text>
                    <text x="100" y="114" text-anchor="middle" font-family="IBM Plex Mono, monospace" font-size="8.5" fill="rgba(247,245,240,0.55)">EST. LOT LOG</text>
                </svg>
            </div>

            <div class="brp-ticker">
                <span class="dot" aria-hidden="true"></span>
                LOT-2026-0716 &mdash; terverifikasi &amp; dirilis otomatis
            </div>
        </div>

        <!-- Login form -->
        <div class="brp-form-side">
            <div class="brp-card">
                <h1>Masuk ke akun Anda</h1>
                <p class="brp-subtext">Gunakan kredensial yang terdaftar di sistem QA.</p>

                @if (session('status'))
                    <div class="brp-status">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="brp-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                        @error('email')
                            <p class="brp-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="brp-field">
                        <label for="password">Password</label>
                        <div class="brp-password-wrap">
                            <input id="password" type="password" name="password" required autocomplete="current-password">
                            <button type="button" class="brp-password-toggle" id="password-toggle" aria-label="Tampilkan password" aria-pressed="false">
                                <svg class="brp-icon icon-eye" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1.5 10S4.5 4 10 4s8.5 6 8.5 6-3 6-8.5 6-8.5-6-8.5-6z"/><circle cx="10" cy="10" r="2.3"/></svg>
                                <svg class="brp-icon icon-eye-off" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2.5 2.5l15 15"/><path d="M8.3 4.3A8.6 8.6 0 0 1 10 4c5.5 0 8.5 6 8.5 6a15.6 15.6 0 0 1-2.7 3.6M5.9 5.9C3.2 7.5 1.5 10 1.5 10s3 6 8.5 6c1.1 0 2.1-.2 3-.6"/><path d="M8.1 8.1a2.3 2.3 0 0 0 3.2 3.3"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="brp-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="brp-row">
                        <label class="brp-remember">
                            <input type="checkbox" name="remember">
                            Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" class="brp-forgot">Lupa password?</a>
                    </div>

                    <button type="submit" class="brp-submit">Masuk</button>
                </form>

                <p class="brp-footnote">Akses hanya untuk yang berwenang.</p>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/login.js') }}?v=2" defer></script>

</body>
</html>
