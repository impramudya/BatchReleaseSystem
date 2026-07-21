@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Selamat datang, {{ auth()->user()->name }}!</h3>
        <p class="text-gray-500 dark:text-gray-400">Kamu berhasil login ke sistem.</p>
    </div>

    @if (session('force_password_change'))
        <div id="password-modal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg shadow-lg max-w-sm w-full p-6 text-center">
                @if (session('password_change_reason') === 'first_login')
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Ganti Password Anda</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Ini adalah login pertama Anda. Untuk keamanan, silakan ganti password default Anda.
                    </p>
                @else
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Password Anda Sudah Kedaluwarsa</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Password Anda sudah digunakan lebih dari 90 hari. Silakan ganti password untuk melanjutkan.
                    </p>
                @endif

                <a href="{{ route('password.change') }}"
                    class="block w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                    Ganti Password Sekarang
                </a>
            </div>
        </div>
    @endif
@endsection