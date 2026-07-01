<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BatchReleaseSystem</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans bg-gray-100 min-h-screen">

    <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
        <h1 class="text-lg font-semibold">BatchReleaseSystem</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
        </form>
    </nav>

    <div class="max-w-4xl mx-auto mt-10 bg-white rounded-lg shadow p-8">
        <h2 class="text-xl font-bold mb-2">Selamat datang, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-600">Kamu berhasil login ke sistem.</p>
    </div>

    @if (session('force_password_change'))
        <div id="password-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6 text-center">
                @if (session('password_change_reason') === 'first_login')
                    <h3 class="text-lg font-semibold mb-2">Ganti Password Anda</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Ini adalah login pertama Anda. Untuk keamanan, silakan ganti password default Anda.
                    </p>
                @else
                    <h3 class="text-lg font-semibold mb-2">Password Anda Sudah Kedaluwarsa</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Password Anda sudah digunakan lebih dari 90 hari. Silakan ganti password untuk melanjutkan.
                    </p>
                @endif

                <a href="{{ route('password.change') }}"
                    class="block w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                    Ganti Password Sekarang
                </a>
            </div>
        </div>
    @endif

</body>
</html>