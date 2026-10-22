<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    @php
        $userRole = auth()->user()->role ?? null;
        $isSuperAdmin = $userRole === 'Super Admin';
        $isAdmin = $userRole === 'Admin';
        $isSupervisor = $userRole === 'Supervisor';
        $isManager = $userRole === 'Manager';

        $canSeeDashboard = $isSuperAdmin || $isAdmin || $isSupervisor || $isManager;
        $canSeeBuatForm = $isSuperAdmin || $isAdmin;
        $canSeeBatchRepository = $isSuperAdmin || $isAdmin || $isSupervisor || $isManager;
        $canSeeTaskApproval = $isSuperAdmin || $isSupervisor || $isManager;
        $canSeeMasterData = $isSuperAdmin;
        $canSeeAuditTrail = $isSuperAdmin || $isAdmin || $isSupervisor || $isManager;
        $canSeeUserManagement = $isSuperAdmin;
    @endphp

    {{-- SIDEBAR --}}
    <aside class="brp-sidebar" id="brp-sidebar">

        <div class="brp-logo-block">
            <p class="brp-logo"><span>BatchRelease</span><span class="accent">Pro</span></p>
            <p class="brp-logo-sub">Batch Release System &mdash; QA</p>
        </div>

        <nav class="brp-nav">

            @if ($canSeeDashboard)
                <div class="brp-nav-group">
                    <p class="brp-nav-label">Overview</p>
                    <a href="{{ route('dashboard') }}" class="brp-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="2.5" width="6.5" height="6.5" rx="1"/><rect x="11" y="2.5" width="6.5" height="6.5" rx="1"/><rect x="2.5" y="11" width="6.5" height="6.5" rx="1"/><rect x="11" y="11" width="6.5" height="6.5" rx="1"/></svg>
                        Dashboard
                    </a>
                </div>
            @endif

            @if ($canSeeBuatForm || $canSeeBatchRepository || $canSeeTaskApproval)
                <div class="brp-nav-group">
                    <p class="brp-nav-label">Batch Management</p>

                    @if ($canSeeBuatForm)
                        @php
                            $batchLineCode = optional(request()->route('line'))->code;
                            $isBatchFormRoute = request()->routeIs('batch-form.create');
                            $isBatchInhouseOpen = $isBatchFormRoute && in_array($batchLineCode, ['inhouse_stripping', 'inhouse_bottling']);
                        @endphp

                        <div class="brp-nav-parent {{ $isBatchFormRoute ? 'is-open' : '' }}">
                            <button type="button" class="brp-nav-link brp-nav-toggle" data-nav-toggle>
                                <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 3.5h12v13H4z"/><path d="M7.5 8h5M7.5 11h5M10 13.5v-3M8.5 12h3"/></svg>
                                <span class="brp-nav-text">Create New Form</span>
                                <svg class="brp-icon brp-nav-caret" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 6l4 4-4 4"/></svg>
                            </button>

                            <div class="brp-nav-submenu">
                                <div class="brp-nav-parent brp-nav-parent-nested {{ $isBatchInhouseOpen ? 'is-open' : '' }}">
                                    <button type="button" class="brp-nav-link brp-nav-sublink brp-nav-toggle" data-nav-toggle>
                                        <span class="brp-nav-text">Inhouse</span>
                                        <svg class="brp-icon brp-nav-caret" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 6l4 4-4 4"/></svg>
                                    </button>
                                    <div class="brp-nav-submenu brp-nav-submenu-nested">
                                        <a href="{{ route('batch-form.create', 'inhouse_stripping') }}"
                                           class="brp-nav-link brp-nav-subsublink {{ $isBatchFormRoute && $batchLineCode === 'inhouse_stripping' ? 'is-active' : '' }}">
                                            Stripping
                                        </a>
                                        <a href="{{ route('batch-form.create', 'inhouse_bottling') }}"
                                           class="brp-nav-link brp-nav-subsublink {{ $isBatchFormRoute && $batchLineCode === 'inhouse_bottling' ? 'is-active' : '' }}">
                                            Bottling
                                        </a>
                                    </div>
                                </div>

                                <a href="{{ route('batch-form.create', 'toll_out') }}"
                                   class="brp-nav-link brp-nav-sublink {{ $isBatchFormRoute && $batchLineCode === 'toll_out' ? 'is-active' : '' }}">
                                    Toll Out
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($canSeeBatchRepository)
                        <a href="{{ route('batch-repository.index') }}" class="brp-nav-link {{ request()->routeIs('batch-repository.*') ? 'is-active' : '' }}">
                            <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="3.5" width="15" height="3.5" rx="0.8"/><path d="M3.5 7v8.5a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V7"/><path d="M8 10.5h4"/></svg>
                            Batch Repository
                        </a>
                    @endif

                    @if ($canSeeTaskApproval)
                        <a href="{{ route('task-approval.index') }}" class="brp-nav-link {{ request()->routeIs('task-approval.*') ? 'is-active' : '' }}">
                            <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="7"/><path d="M7 10.2l2 2 4-4.3"/></svg>
                            Task Approval
                            @if (isset($sidebarPendingApprovalCount) && $sidebarPendingApprovalCount > 0)
                                <span class="brp-nav-badge">{{ $sidebarPendingApprovalCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            @endif

            @if ($canSeeMasterData)
                <div class="brp-nav-group">
                    <p class="brp-nav-label">Master Data</p>
                    <a href="{{ route('product-master.index') }}" class="brp-nav-link {{ request()->routeIs('product-master.*') ? 'is-active' : '' }}">
                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6.5L10 3l7 3.5-7 3.5-7-3.5z"/><path d="M3 6.5V14l7 3.5 7-3.5V6.5"/><path d="M10 10v7.5"/></svg>
                        Product Master
                    </a>

                    @php
                        $currentLineCode = optional(request()->route('line'))->code;
                        $isChecklistRoute = request()->routeIs('checklist-config.*');
                        $isInhouseGroupOpen = $isChecklistRoute && in_array($currentLineCode, ['inhouse_stripping', 'inhouse_bottling']);
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
                                       class="brp-nav-link brp-nav-subsublink {{ $isChecklistRoute && $currentLineCode === 'inhouse_stripping' ? 'is-active' : '' }}">
                                        Stripping
                                    </a>
                                    <a href="{{ route('checklist-config.index', 'inhouse_bottling') }}"
                                       class="brp-nav-link brp-nav-subsublink {{ $isChecklistRoute && $currentLineCode === 'inhouse_bottling' ? 'is-active' : '' }}">
                                        Bottling
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('checklist-config.index', 'toll_out') }}"
                               class="brp-nav-link brp-nav-sublink {{ $isChecklistRoute && $currentLineCode === 'toll_out' ? 'is-active' : '' }}">
                                Toll Out
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($canSeeAuditTrail || $canSeeUserManagement)
                <div class="brp-nav-group">
                    <p class="brp-nav-label">System</p>

                    @if ($canSeeAuditTrail)
                        <a href="{{ route('audit-trail.index') }}" class="brp-nav-link {{ request()->routeIs('audit-trail.*') ? 'is-active' : '' }}">
                            <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="7"/><path d="M10 6v4l2.6 1.6"/></svg>
                            Audit Trail
                        </a>
                    @endif

                    @if ($canSeeUserManagement)
                        <a href="{{ route('user-management.index') }}" class="brp-nav-link {{ request()->routeIs('user-management.*') ? 'is-active' : '' }}">
                            <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7.2" cy="7" r="2.3"/><path d="M2.8 16c.5-2.7 2.3-4.2 4.4-4.2s3.9 1.5 4.4 4.2"/><circle cx="14" cy="7.5" r="1.9"/><path d="M12.5 11.9c1.7.1 3.1 1.5 3.5 3.8"/></svg>
                            User Management
                        </a>
                    @endif
                </div>
            @endif

        </nav>

        <div class="brp-user-wrap" id="user-menu-wrap">
            <div class="brp-user-menu">
                <button id="theme-toggle" type="button" class="brp-theme-toggle">
                    <span>Theme Mode</span>
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

    <div class="brp-sidebar-overlay" id="sidebar-overlay"></div>

    {{-- MAIN CONTENT --}}
    <div class="brp-main">

        <header class="brp-header">
            <div class="brp-header-left">
                <button class="brp-hamburger" id="sidebar-toggle" type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="brp-sidebar">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 5.5h14M3 10h14M3 14.5h14"/></svg>
                </button>
                <h2 class="brp-page-title">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="brp-header-actions">
                @yield('header-actions')
                <div class="brp-notif-wrap" id="notif-wrap">
                    <button class="brp-bell" type="button" id="notif-trigger" aria-label="Notifications" aria-haspopup="true" aria-expanded="false">
                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8a5 5 0 0 1 10 0c0 3.2 1 4.3 1 4.3H4S5 11.2 5 8z"/><path d="M8.2 15.3a1.8 1.8 0 0 0 3.6 0"/></svg>
                        <span class="brp-bell-dot" id="notif-dot" aria-hidden="true" style="display: none;"></span>
                    </button>

                    <div class="brp-notif-dropdown" id="notif-dropdown">
                        <div class="brp-notif-header">
                            <span>Notifications</span>
                            <button type="button" id="notif-mark-all">Mark all as read</button>
                        </div>
                        <div class="brp-notif-list" id="notif-list">
                            <p class="brp-notif-empty">Loading...</p>
                        </div>
                    </div>
                </div>
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

    <script>
        (function () {
            const trigger = document.getElementById('notif-trigger');
            const dropdown = document.getElementById('notif-dropdown');
            const list = document.getElementById('notif-list');
            const dot = document.getElementById('notif-dot');
            const markAllBtn = document.getElementById('notif-mark-all');
            if (!trigger) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            function renderNotifications(notifications) {
                if (!notifications.length) {
                    list.innerHTML = '<p class="brp-notif-empty">No notifications yet.</p>';
                    return;
                }
                list.innerHTML = notifications.map(function (n) {
                    return '<a href="' + n.url + '" data-id="' + n.id + '" class="brp-notif-item' + (n.is_read ? '' : ' is-unread') + '">' +
                        '<p class="brp-notif-title">' + escapeHtml(n.title) + '</p>' +
                        '<p class="brp-notif-message">' + escapeHtml(n.message) + '</p>' +
                        '<p class="brp-notif-time">' + escapeHtml(n.time) + '</p>' +
                        '</a>';
                }).join('');
            }

            function loadNotifications() {
                fetch('{{ route('notifications.index') }}', {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        renderNotifications(data.notifications);
                        dot.style.display = data.unread_count > 0 ? 'block' : 'none';
                    })
                    .catch(function () {
                        list.innerHTML = '<p class="brp-notif-empty">Failed to load notifications.</p>';
                    });
            }

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = dropdown.classList.toggle('is-open');
                trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                if (isOpen) loadNotifications();
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && e.target !== trigger) {
                    dropdown.classList.remove('is-open');
                }
            });

            list.addEventListener('click', function (e) {
                const item = e.target.closest('.brp-notif-item');
                if (!item) return;
                fetch('/notifications/' + item.dataset.id + '/read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                });
            });

            markAllBtn?.addEventListener('click', function () {
                fetch('{{ route('notifications.read-all') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                }).then(loadNotifications);
            });


            loadNotifications();
            setInterval(loadNotifications, 30000);
        })();
    </script>

    <style>
        .brp-notif-wrap { position: relative; }
        .brp-notif-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            width: 320px;
            max-height: 380px;
            background: var(--content-surface);
            border: 1px solid var(--content-border);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            z-index: 50;
            overflow: hidden;
        }
        .brp-notif-dropdown.is-open { display: flex; flex-direction: column; }
        .brp-notif-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.7rem 1rem; border-bottom: 1px solid var(--content-border);
            font-family: "Poppins", sans-serif; font-size: 0.82rem; font-weight: 600;
            color: var(--content-text);
        }
        .brp-notif-header button {
            background: none; border: none; color: var(--primary);
            font-size: 0.72rem; font-weight: 600; cursor: pointer; text-decoration: none;
        }
        .brp-notif-list { overflow-y: auto; }
        .brp-notif-item {
            display: block; padding: 0.7rem 1rem; text-decoration: none;
            border-bottom: 1px solid var(--content-border);
            transition: background 0.15s ease;
        }
        .brp-notif-item:hover { background: #f2f5f2; }
        .brp-notif-item.is-unread { background: #edf5e9; }
        .brp-notif-title { margin: 0; font-size: 0.82rem; font-weight: 600; color: var(--content-text); }
        .brp-notif-message { margin: 0.15rem 0 0; font-size: 0.78rem; color: var(--content-text-soft); }
        .brp-notif-time { margin: 0.25rem 0 0; font-size: 0.7rem; color: var(--content-text-soft); }
        .brp-notif-empty { padding: 1rem; text-align: center; font-size: 0.8rem; color: var(--content-text-soft); }
    </style>

    @stack('scripts')

</body>
</html>
