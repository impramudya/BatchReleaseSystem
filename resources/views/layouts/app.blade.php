<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BatchReleasePro')</title>

    {{-- Cegah flicker: set tema sebelum CSS/JS load --}}
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = saved ? saved === 'dark' : prefersDark;
            if (isDark) document.documentElement.classList.add('dark');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/theme.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Poppins:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #101c2c;
            --ink-line: rgba(247, 245, 240, 0.14);
            --paper: #f7f5f0;
            --surface: #ffffff;
            --teal: #0e7c7b;
            --teal-dark: #0a5f5e;
            --teal-tint: #e7f2f1;
            --amber: #c08a28;
            --slate: #29323c;
            --slate-soft: #66707c;
            --danger: #b3452f;
            --border: #e3ddcf;

            --content-bg: var(--paper);
            --content-surface: var(--surface);
            --content-text: var(--slate);
            --content-text-soft: var(--slate-soft);
            --content-border: var(--border);
        }

        html.dark {
            --content-bg: #0b1622;
            --content-surface: #101c2c;
            --content-text: #e7e4da;
            --content-text-soft: #9aa3ad;
            --content-border: rgba(247, 245, 240, 0.12);
        }

        * { box-sizing: border-box; }

        body.brp-body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            font-family: 'Poppins', sans-serif;
            background: var(--content-bg);
            color: var(--content-text);
        }

        .brp-icon { width: 18px; height: 18px; flex-shrink: 0; }

        /* ---------- Sidebar: always the "ink" instrument panel ---------- */
        .brp-sidebar {
            width: 15.5rem;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            background: var(--ink);
            color: var(--paper);
            display: flex;
            flex-direction: column;
            background-image:
                radial-gradient(circle at 15% 0%, rgba(14, 124, 123, 0.16), transparent 45%),
                radial-gradient(circle at 100% 100%, rgba(192, 138, 40, 0.08), transparent 50%);
        }

        .brp-logo-block {
            padding: 1.75rem 1.5rem 1.5rem;
        }

        .brp-logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: -0.01em;
            margin: 0;
        }
        .brp-logo .accent { color: var(--teal); }

        .brp-logo-sub {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Poppins', monospace;
            font-size: 0.63rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(247, 245, 240, 0.45);
            margin: 0.55rem 0 0;
        }
        .brp-logo-sub::before {
            content: "";
            width: 14px;
            height: 1px;
            background: var(--amber);
        }

        .brp-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(247, 245, 240, 0.18) transparent;
        }
        .brp-nav::-webkit-scrollbar { width: 5px; }
        .brp-nav::-webkit-scrollbar-track { background: transparent; }
        .brp-nav::-webkit-scrollbar-thumb {
            background: rgba(247, 245, 240, 0.16);
            border-radius: 999px;
        }
        .brp-nav::-webkit-scrollbar-thumb:hover { background: rgba(247, 245, 240, 0.3); }

        .brp-nav-group { margin-bottom: 1.5rem; }
        .brp-nav-group:last-child { margin-bottom: 0; }

        .brp-nav-label {
            font-family: 'Poppins', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(247, 245, 240, 0.38);
            padding: 0 0.6rem;
            margin: 0 0 0.5rem;
        }

        .brp-nav-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem 0.6rem;
            margin-bottom: 0.15rem;
            border-radius: 3px;
            border-left: 2px solid transparent;
            font-size: 0.86rem;
            color: rgba(247, 245, 240, 0.62);
            text-decoration: none;
            transition: background 0.12s ease, color 0.12s ease, border-color 0.12s ease;
        }

        .brp-nav-link:hover {
            background: rgba(247, 245, 240, 0.06);
            color: var(--paper);
        }

        .brp-nav-link.is-active {
            background: rgba(14, 124, 123, 0.16);
            border-left-color: var(--teal);
            color: var(--paper);
        }

        .brp-nav-count {
            margin-left: auto;
            background: var(--danger);
            color: var(--paper);
            font-family: 'Poppins', monospace;
            font-size: 0.68rem;
            line-height: 1;
            padding: 0.28rem 0.42rem;
            border-radius: 999px;
        }

        .brp-user-wrap {
            position: relative;
            border-top: 1px solid var(--ink-line);
        }

        .brp-user-block {
            width: 100%;
            padding: 1rem 1.4rem;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            cursor: poPoppins;
            text-align: left;
        }
        .brp-user-block:hover { background: rgba(247, 245, 240, 0.05); }
        .brp-user-block:focus-visible { outline: 2px solid var(--teal); outline-offset: -2px; }

        .brp-user-caret {
            margin-left: auto;
            color: rgba(247, 245, 240, 0.4);
            transition: transform 0.15s ease;
        }
        .brp-user-wrap.is-open .brp-user-caret { transform: rotate(180deg); }

        .brp-user-menu {
            position: absolute;
            left: 0.75rem;
            right: 0.75rem;
            bottom: calc(100% + 0.5rem);
            background: #17263a;
            border: 1px solid var(--ink-line);
            border-radius: 6px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
            padding: 0.4rem;
            display: none;
        }
        .brp-user-wrap.is-open .brp-user-menu { display: block; }

        .brp-theme-toggle {
            width: 100%;
            padding: 0.6rem 0.65rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            background: transparent;
            border: none;
            border-radius: 4px;
            color: rgba(247, 245, 240, 0.75);
            font-family: 'Poppins', sans-serif;
            font-size: 0.83rem;
            cursor: poPoppins;
        }
        .brp-theme-toggle:hover { color: var(--paper); background: rgba(247, 245, 240, 0.06); }
        .brp-theme-toggle:focus-visible { outline: 2px solid var(--teal); outline-offset: 2px; }

        .brp-theme-icons { display: flex; align-items: center; }
        .icon-sun, .icon-moon { display: none; }
        html:not(.dark) .icon-sun { display: block; }
        html.dark .icon-moon { display: block; }

        .brp-status-line {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.9rem 1.4rem 0.6rem;
            font-family: 'Poppins', monospace;
            font-size: 0.66rem;
            letter-spacing: 0.06em;
            color: rgba(247, 245, 240, 0.4);
        }
        .brp-status-dot {
            width: 6px; height: 6px;
            border-radius: 999px;
            background: #6fc7ac;
            box-shadow: 0 0 0 3px rgba(111, 199, 172, 0.15);
        }

        .brp-avatar {
            width: 34px; height: 34px;
            border-radius: 6px;
            background: var(--teal);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--ink);
            flex-shrink: 0;
        }

        .brp-user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--paper);
            margin: 0;
            line-height: 1.3;
        }
        .brp-user-role {
            font-family: 'Poppins', monospace;
            font-size: 0.66rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--amber);
            margin: 0.15rem 0 0;
        }

        /* ---------- Main ---------- */
        .brp-main {
            margin-left: 15.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .brp-header {
            background: var(--content-surface);
            border-bottom: 1px solid var(--content-border);
            padding: 1rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brp-page-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            color: var(--content-text);
        }

        .brp-header-actions {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .brp-bell {
            position: relative;
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 5px;
            border: 1px solid var(--content-border);
            background: transparent;
            color: var(--content-text-soft);
            cursor: poPoppins;
        }
        .brp-bell:hover { color: var(--content-text); border-color: var(--teal); }
        .brp-bell-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 6px; height: 6px;
            background: var(--danger);
            border-radius: 999px;
        }

        .brp-logout {
            background: none;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.83rem;
            color: var(--content-text-soft);
            cursor: poPoppins;
            padding: 0.4rem 0.2rem;
        }
        .brp-logout:hover { color: var(--danger); }

        .brp-content {
            flex: 1;
            padding: 1.75rem;
        }

        @media (max-width: 900px) {
            .brp-sidebar { display: none; }
            .brp-main { margin-left: 0; }
        }
    </style>
</head>
<body class="brp-body">

    {{-- SIDEBAR --}}
    <aside class="brp-sidebar">

        <div class="brp-logo-block">
            <p class="brp-logo"><span>BatchRelease</span><span class="accent">Pro</span></p>
            <p class="brp-logo-sub">Sistem Rilis Batch &mdash; QA</p>
        </div>

        <nav class="brp-nav">

            <div class="brp-nav-group">
                <p class="brp-nav-label">Overview</p>
                <a href="{{ route('dashboard') }}" class="brp-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="2.5" width="6.5" height="6.5" rx="1"/><rect x="11" y="2.5" width="6.5" height="6.5" rx="1"/><rect x="2.5" y="11" width="6.5" height="6.5" rx="1"/><rect x="11" y="11" width="6.5" height="6.5" rx="1"/></svg>
                    Dashboard
                </a>
            </div>

            <div class="brp-nav-group">
                <p class="brp-nav-label">Batch Management</p>
                <a href="#" class="brp-nav-link">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 3.5h12v13H4z"/><path d="M7.5 8h5M7.5 11h5M10 13.5v-3M8.5 12h3"/></svg>
                    Buat Form Baru
                </a>
                <a href="#" class="brp-nav-link">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="3.5" width="15" height="3.5" rx="0.8"/><path d="M3.5 7v8.5a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V7"/><path d="M8 10.5h4"/></svg>
                    Batch Repository
                </a>
                <a href="#" class="brp-nav-link">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="7"/><path d="M7 10.2l2 2 4-4.3"/></svg>
                    Task Approval
                </a>
            </div>

            <div class="brp-nav-group">
                <p class="brp-nav-label">Master Data</p>
                <a href="#" class="brp-nav-link">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6.5L10 3l7 3.5-7 3.5-7-3.5z"/><path d="M3 6.5V14l7 3.5 7-3.5V6.5"/><path d="M10 10v7.5"/></svg>
                    Product Master
                </a>
                <a href="#" class="brp-nav-link">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 5h.01M5 10h.01M5 15h.01"/><path d="M8.5 5h8M8.5 10h8M8.5 15h8"/></svg>
                    Checklist Config
                </a>
            </div>

            <div class="brp-nav-group">
                <p class="brp-nav-label">System</p>
                <a href="#" class="brp-nav-link">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="7"/><path d="M10 6v4l2.6 1.6"/></svg>
                    Audit Trail
                </a>
                <a href="{{ route('user-management.index') }}" class="brp-nav-link {{ request()->routeIs('user-management.*') ? 'is-active' : '' }}">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7.2" cy="7" r="2.3"/><path d="M2.8 16c.5-2.7 2.3-4.2 4.4-4.2s3.9 1.5 4.4 4.2"/><circle cx="14" cy="7.5" r="1.9"/><path d="M12.5 11.9c1.7.1 3.1 1.5 3.5 3.8"/></svg>
                    User Management
                </a>
            </div>

        </nav>

        <div class="brp-status-line">
            <span class="brp-status-dot" aria-hidden="true"></span>
            Sistem aktif &mdash; tersinkron
        </div>

        <div class="brp-user-wrap" id="user-menu-wrap">
            <div class="brp-user-menu">
                <button id="theme-toggle" type="button" class="brp-theme-toggle">
                    <span>Mode Tampilan</span>
                    <span id="theme-icon" class="brp-theme-icons">
                        <svg class="brp-icon icon-sun" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="3.3"/><path d="M10 2.5v2M10 15.5v2M17.5 10h-2M4.5 10h-2M15.3 4.7l-1.4 1.4M6.1 13.9l-1.4 1.4M15.3 15.3l-1.4-1.4M6.1 6.1L4.7 4.7"/></svg>
                        <svg class="brp-icon icon-moon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16.5 12.3A6.8 6.8 0 0 1 7.7 3.5a6.8 6.8 0 1 0 8.8 8.8z"/></svg>
                    </span>
                </button>
            </div>

            <button type="button" class="brp-user-block" id="user-menu-trigger" aria-haspopup="true" aria-expanded="false">
                <div class="brp-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <p class="brp-user-name">{{ auth()->user()->name }}</p>
                    <p class="brp-user-role">{{ auth()->user()->role ?? 'User' }}</p>
                </div>
                <svg class="brp-icon brp-user-caret" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5.5 12.5L10 8l4.5 4.5"/></svg>
            </button>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="brp-main">

        <header class="brp-header">
            <h2 class="brp-page-title">@yield('page-title', 'Dashboard')</h2>
            <div class="brp-header-actions">
                @yield('header-actions')
                <button class="brp-bell" type="button" aria-label="Notifikasi">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8a5 5 0 0 1 10 0c0 3.2 1 4.3 1 4.3H4S5 11.2 5 8z"/><path d="M8.2 15.3a1.8 1.8 0 0 0 3.6 0"/></svg>
                    <span class="brp-bell-dot" aria-hidden="true"></span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="brp-logout">Logout</button>
                </form>
            </div>
        </header>

        <main class="brp-content">
            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            const wrap = document.getElementById('user-menu-wrap');
            const trigger = document.getElementById('user-menu-trigger');
            if (!wrap || !trigger) return;

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = wrap.classList.toggle('is-open');
                trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) {
                    wrap.classList.remove('is-open');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    wrap.classList.remove('is-open');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        })();
    </script>

</body>
</html>
