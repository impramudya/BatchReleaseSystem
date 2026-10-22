@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-management.css') }}?v={{ filemtime(public_path('css/user-management.css')) }}">
@endpush

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')

    <div class="um-breadcrumb">
        <a href="{{ route('user-management.index') }}">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 4.5L6 10l6 5.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            User Management
        </a>
        <span class="sep">/</span>
        <span class="current">Add User</span>
    </div>

    <form action="{{ route('user-management.store') }}" method="POST">
        @csrf

        <div class="um-form-card">
            <div class="um-form-head">
                <h3 class="um-form-title">Add New User</h3>
                <p class="um-form-sub">The account will be able to log in immediately after creation.</p>
            </div>

            <div class="um-form-body">
                @include('user-management._form')
            </div>

            <div class="um-form-actions">
                <button type="submit" class="um-btn-primary">Save User</button>
                <a href="{{ route('user-management.index') }}" class="um-btn-ghost">Cancel</a>
            </div>
        </div>
    </form>

@endsection
