<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - BatchReleaseSystem</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Ganti Password</h1>

        @if (session('warning'))
            <div class="mb-4 text-sm text-yellow-700 bg-yellow-50 p-3 rounded">
                {{ session('warning') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf

            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Password Lama</label>
                <input id="current_password" type="password" name="current_password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('current_password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                Simpan Password Baru
            </button>
        </form>

    </div>

</body>
</html>