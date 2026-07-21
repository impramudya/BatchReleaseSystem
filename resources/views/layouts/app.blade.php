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
</head>
<body class="font-sans bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-200 min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col fixed h-screen">

        <div class="p-5 border-b border-gray-200 dark:border-gray-800">
            <h1 class="text-lg font-bold">
                <span class="text-gray-900 dark:text-white">BatchRelease</span><span class="text-blue-600 dark:text-blue-500">Pro</span>
            </h1>
            <p class="text-xs text-gray-500">Quality Assurance System</p>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide px-3 mb-2">Overview</p>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-md text-sm {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <span>▦</span> Dashboard
                </a>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide px-3 mb-2">Batch Management</p>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <span>📄</span> Buat Form Baru
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <span>🗂</span> Batch Repository
                </a>
                <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <span class="flex items-center gap-3"><span>✔</span> Task Approval</span>
                    <span class="bg-red-600 text-white text-xs rounded-full px-2 py-0.5">3</span>
                </a>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide px-3 mb-2">Master Data</p>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <span>📦</span> Product Master
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <span>☰</span> Checklist Config
                </a>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide px-3 mb-2">System</p>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <span>🕒</span> Audit Trail
                </a>
                <a href="{{ route('user-management.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-md text-sm {{ request()->routeIs('user-management.*') ? 'bg-blue-600 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <span>👥</span> User Management
                </a>
            </div>

        </nav>

        {{-- Toggle tema --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            <button id="theme-toggle" type="button"
                class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <span class="flex items-center gap-2">
                    <span id="theme-icon">🌙</span> Mode Tampilan
                </span>
            </button>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            <div class="flex gap-2 mb-3">
                <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">{{ auth()->user()->role ?? 'User' }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-xs font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'User' }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="ml-64 flex-1 flex flex-col min-h-screen">

        <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h2>
            <div class="flex items-center gap-3">
                @yield('header-actions')
                <button class="relative p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                    🔔
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-600 rounded-full"></span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-red-600">Logout</button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>