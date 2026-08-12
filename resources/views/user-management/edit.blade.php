@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/user-management.css') }}">

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="um-breadcrumb">
        <a href="{{ route('user-management.index') }}">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 4.5L6 10l6 5.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            User Management
        </a>
        <span class="sep">/</span>
        <span class="current">Edit {{ $user->name }}</span>
    </div>

    <form action="{{ route('user-management.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="um-form-card">
            <div class="um-form-head">
                <h3 class="um-form-title">Edit Pengguna</h3>
                {{-- <p class="um-form-sub">
                    Login terakhir:
                    {{ $user->last_login ? $user->last_login->format('d M Y, H:i') : 'Belum pernah login' }}
                </p> --}}
            </div>

            <div class="um-form-body">
                @include('user-management._form')
            </div>

            <div class="um-form-actions">
                <button type="submit" class="um-btn-primary">Simpan Perubahan</button>
                <a href="{{ route('user-management.index') }}" class="um-btn-ghost">Batal</a>
            </div>
        </div>
    </form>

@endsection
