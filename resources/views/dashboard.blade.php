@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="brp-welcome-card">
        <h3>Selamat datang, {{ auth()->user()->name }}!</h3>
        <p>Kamu berhasil login ke sistem.</p>
    </div>

    @if (session('force_password_change'))
        <div id="password-modal" class="brp-modal-overlay">
            <div class="brp-modal-box">
                @if (session('password_change_reason') === 'first_login')
                    <h3>Ganti Password Anda</h3>
                    <p>Ini adalah login pertama Anda. Untuk keamanan, silakan ganti password default Anda.</p>
                @else
                    <h3>Password Anda Sudah Kedaluwarsa</h3>
                    <p>Password Anda sudah digunakan lebih dari 90 hari. Silakan ganti password untuk melanjutkan.</p>
                @endif

                <a href="{{ route('password.change') }}" class="brp-modal-btn">
                    Ganti Password Sekarang
                </a>
            </div>
        </div>
    @endif
@endsection
