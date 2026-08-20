<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BatchReleasePro')</title>
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

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="brp-body">

    {{-- SIDEBAR --}}
    <aside class="brp-sidebar" id="brp-sidebar">

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
                <a href="{{ route('batch-form.create') }}" class="brp-nav-link {{ request()->routeIs('batch-form.*') ? 'is-active' : '' }}">
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
                <a href="{{ route('product-master.index') }}" class="brp-nav-link {{ request()->routeIs('product-master.*') ? 'is-active' : '' }}">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6.5L10 3l7 3.5-7 3.5-7-3.5z"/><path d="M3 6.5V14l7 3.5 7-3.5V6.5"/><path d="M10 10v7.5"/></svg>
                    Product Master
                </a>
                <a href="{{ route('category-master.index') }}" class="brp-nav-link {{ request()->routeIs('category-master.*') ? 'is-active' : '' }}">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 3.5a2 2 0 1 1 4 0v1h2.5a1.5 1.5 0 0 1 1.5 1.5v2.5h-1a2 2 0 1 0 0 4h1v2.5a1.5 1.5 0 0 1-1.5 1.5H12v-1a2 2 0 1 0-4 0v1H5.5A1.5 1.5 0 0 1 4 15v-2.5h1a2 2 0 1 0 0-4H4V6a1.5 1.5 0 0 1 1.5-1.5H8v-1z"/>
                    </svg>
                    Category Master
                </a>

                @php
                    $currentLineCode = optional(request()->route('line'))->code;
                    $isChecklistRoute = request()->routeIs('checklist-config.*');
                    $isInhouseGroupOpen = in_array($currentLineCode, ['inhouse_stripping', 'inhouse_bottling']);
                @endphp

                <div class="brp-nav-parent {{ $isChecklistRoute ? 'is-open' : '' }}">
                    <button type="button" class="brp-nav-link brp-nav-toggle" data-nav-toggle>
                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 5h.01M5 10h.01M5 15h.01"/><path d="M8.5 5h8M8.5 10h8M8.5 15h8"/></svg>
                        <span class="brp-nav-text">Checklist Config</span>
                        <svg class="brp-icon brp-nav-caret" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 6l4 4-4 4"/></svg>
                    </button>

                    <div class="brp-nav-submenu">
                        <div class="brp-nav-parent brp-nav-parent-nested {{ $isInhouseGroupOpen ? 'is-open' : '' }}">
                            <button type="button" class="brp-nav-link brp-nav-sublink brp-nav-toggle" data-nav-toggle>
                                <span class="brp-nav-text">Inhouse</span>
                                <svg class="brp-icon brp-nav-caret" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 6l4 4-4 4"/></svg>
                            </button>
                            <div class="brp-nav-submenu brp-nav-submenu-nested">
                                <a href="{{ route('checklist-config.index', 'inhouse_stripping') }}"
                                   class="brp-nav-link brp-nav-subsublink {{ $currentLineCode === 'inhouse_stripping' ? 'is-active' : '' }}">
                                    Stripping
                                </a>
                                <a href="{{ route('checklist-config.index', 'inhouse_bottling') }}"
                                   class="brp-nav-link brp-nav-subsublink {{ $currentLineCode === 'inhouse_bottling' ? 'is-active' : '' }}">
                                    Bottling
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('checklist-config.index', 'toll_out') }}"
                           class="brp-nav-link brp-nav-sublink {{ $currentLineCode === 'toll_out' ? 'is-active' : '' }}">
                            Toll Out
                        </a>
                    </div>
                </div>
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

    {{-- OVERLAY untuk mobile (klik area gelap = tutup sidebar) --}}
    <div class="brp-sidebar-overlay" id="sidebar-overlay"></div>

    {{-- MAIN CONTENT --}}
    <div class="brp-main">

        <header class="brp-header">
            <div class="brp-header-left">
                <button class="brp-hamburger" id="sidebar-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="brp-sidebar">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 5.5h14M3 10h14M3 14.5h14"/></svg>
                </button>
                <h2 class="brp-page-title">@yield('page-title', 'Dashboard')</h2>
            </div>
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

    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('scripts')

</body>
</html>
