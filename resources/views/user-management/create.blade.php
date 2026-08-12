@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/user-management.css') }}">

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')

    <div class="um-breadcrumb">
        <a href="{{ route('user-management.index') }}">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 4.5L6 10l6 5.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            User Management
        </a>
        <span class="sep">/</span>
        <span class="current">Tambah User</span>
    </div>

    <form action="{{ route('user-management.store') }}" method="POST">
        @csrf

        <div class="um-form-card">
            <div class="um-form-head">
                <h3 class="um-form-title">Tambah Pengguna Baru</h3>
                <p class="um-form-sub">Akun akan langsung bisa login setelah dibuat.</p>
            </div>

            <div class="um-form-body">
                @include('user-management._form')
            </div>

            <div class="um-form-actions">
                <button type="submit" class="um-btn-primary">Simpan User</button>
                <a href="{{ route('user-management.index') }}" class="um-btn-ghost">Batal</a>
            </div>
        </div>
    </form>

@endsection
