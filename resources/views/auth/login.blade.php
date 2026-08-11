<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BatchReleasePro</title>
    @vite('resources/css/auth.css')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #101c2c;
            --ink-line: rgba(247, 245, 240, 0.14);
            --paper: #f7f5f0;
            --teal: #0e7c7b;
            --teal-dark: #0a5f5e;
            --amber: #c08a28;
            --slate: #29323c;
            --slate-soft: #66707c;
            --danger: #b3452f;
        }

        * { box-sizing: border-box; }

        body.brp-body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--slate);
            background: var(--paper);
            display: flex;
        }

        .brp-shell {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            min-height: 100vh;
            width: 100%;
        }

        /* ---------- Left: identity panel ---------- */
        .brp-panel {
            position: relative;
            background: var(--ink);
            color: var(--paper);
            overflow: hidden;
            padding: 3rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 3rem;
            background-image:
                radial-gradient(circle at 22% 28%, rgba(14, 124, 123, 0.22), transparent 46%),
                radial-gradient(circle at 82% 78%, rgba(192, 138, 40, 0.10), transparent 50%);
        }

        .brp-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(247, 245, 240, 0.55);
        }
        .brp-eyebrow::before {
            content: "";
            width: 22px;
            height: 1px;
            background: var(--amber);
        }

        .brp-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 2.1rem;
            margin: 0.85rem 0 0.9rem;
            letter-spacing: -0.015em;
        }

        .brp-tagline {
            max-width: 32ch;
            font-size: 0.98rem;
            line-height: 1.6;
            color: rgba(247, 245, 240, 0.62);
        }

        .brp-seal-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brp-seal {
            width: 186px;
            height: 186px;
            animation: brp-spin 40s linear infinite;
        }
        @media (prefers-reduced-motion: reduce) {
            .brp-seal { animation: none; }
        }
        @keyframes brp-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .brp-ticker {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.02em;
            color: rgba(247, 245, 240, 0.4);
        }
        .brp-ticker .dot {
            width: 5px;
            height: 5px;
            border-radius: 999px;
            background: #6fc7ac;
            flex-shrink: 0;
        }

        /* ---------- Right: form ---------- */
        .brp-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
        }

        .brp-card {
            width: 100%;
            max-width: 380px;
        }

        .brp-card h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 0 0.35rem;
            color: var(--slate);
        }

        .brp-subtext {
            font-size: 0.88rem;
            color: var(--slate-soft);
            margin: 0 0 1.75rem;
        }

        .brp-status {
            font-size: 0.85rem;
            background: #ecf7f2;
            border: 1px solid #bfe3d2;
            color: #1f7a52;
            padding: 0.65rem 0.85rem;
            border-radius: 4px;
            margin-bottom: 1.25rem;
        }

        .brp-field { margin-bottom: 1.15rem; }

        .brp-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--slate);
            margin-bottom: 0.4rem;
        }

        .brp-field input[type="email"],
        .brp-field input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.8rem;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: var(--slate);
            background: #fff;
            border: 1px solid #d7d3c8;
            border-radius: 4px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .brp-field input:focus-visible {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.18);
        }

        .brp-error {
            font-size: 0.8rem;
            color: var(--danger);
            margin: 0.35rem 0 0;
        }

        .brp-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .brp-remember {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.85rem;
            color: var(--slate-soft);
        }

        .brp-remember input {
            width: 15px;
            height: 15px;
            accent-color: var(--teal);
        }

        .brp-forgot {
            font-size: 0.85rem;
            color: var(--teal-dark);
            text-decoration: none;
        }
        .brp-forgot:hover { text-decoration: underline; }
        .brp-forgot:focus-visible { outline: 2px solid var(--teal); outline-offset: 2px; }

        .brp-submit {
            width: 100%;
            padding: 0.72rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--paper);
            background: var(--teal);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .brp-submit:hover { background: var(--teal-dark); }
        .brp-submit:focus-visible { outline: 2px solid var(--ink); outline-offset: 2px; }

        .brp-footnote {
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: var(--slate-soft);
            text-align: center;
        }

        /* ---------- Mobile ---------- */
        @media (max-width: 860px) {
            .brp-shell { grid-template-columns: 1fr; }
            .brp-panel {
                padding: 2.25rem 1.5rem;
                min-height: auto;
                gap: 1.75rem;
            }
            .brp-seal { width: 120px; height: 120px; }
            .brp-ticker { display: none; }
            .brp-form-side { padding: 2rem 1.5rem 3rem; }
        }
    </style>
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
                        <input id="password" type="password" name="password" required autocomplete="current-password">
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

</body>
</html>
