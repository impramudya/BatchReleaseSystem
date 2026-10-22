@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-management.css') }}?v={{ filemtime(public_path('css/user-management.css')) }}">
@endpush

@section('content')

    <div id="um-toast" class="um-toast">
        <svg id="um-toast-icon" class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"></svg>
        <span id="um-toast-text"></span>
    </div>

    <div id="um-modal-overlay" class="um-modal-overlay">
        <div class="um-modal" role="alertdialog" aria-modal="true" aria-labelledby="um-modal-title">
            <div class="um-modal-icon delete" id="um-modal-icon">
                <svg id="um-modal-icon-svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" width="20" height="20">
                    <path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="um-modal-title" id="um-modal-title">Delete this user?</h3>
            <p class="um-modal-text" id="um-modal-text">
                <strong id="um-modal-name"></strong> will permanently lose access. Deleted data cannot be recovered.
            </p>
            <div class="um-modal-actions">
                <button type="button" class="um-modal-cancel" id="um-modal-cancel">Cancel</button>
                <button type="button" class="um-modal-confirm delete" id="um-modal-confirm">Delete User</button>
            </div>
        </div>
    </div>

    <div class="um-card">
        <div class="um-card-head">
            <div>
                <h3 class="um-card-title">User List</h3>
                <p class="um-card-sub">{{ $users->count() }} accounts registered in QA system</p>
            </div>
            <div class="um-head-right">
                <div class="um-search">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M16 16l-3.3-3.3"/></svg>
                    <input type="text" id="um-search-input" placeholder="Search name, NIK, initial, email...">
                </div>
                <a href="{{ route('user-management.create') }}" class="um-add-btn">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 4.5v11M4.5 10h11"/></svg>
                    Add User
                </a>
            </div>
        </div>

        <table class="um-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>NIK</th>
                    <th>Initial</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="um-table-body">
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <span class="um-name">{{ $user->name }}</span>
                        </td>
                        <td><span style="font-family: monospace; font-size: 0.82rem; font-weight: 500;">{{ $user->nik ?? '—' }}</span></td>
                        <td class="um-email">{{ $user->initial ?? '—' }}</td>
                        <td class="um-email">{{ $user->email }}</td>
                        <td class="um-email">{{ $user->role }}</td>
                        <td>
                            @if(!empty($user->locked_at))
                                <span class="um-status" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;" title="Locked at {{ $user->locked_at->format('d M Y H:i') }} (5 failed attempts)"><span class="dot" style="background:#ef4444;"></span> Locked</span>
                            @elseif(($user->status ?? 'active') === 'active')
                                <span class="um-status"><span class="dot"></span> Active</span>
                            @else
                                <span class="um-status inactive"><span class="dot"></span> Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="um-actions">
                                @if(!empty($user->locked_at) || $user->status === 'inactive')
                                <form action="{{ route('user-management.unlock', $user->id) }}" method="POST" class="um-action-form" data-type="unlock" data-name="{{ $user->name }}">
                                    @csrf
                                    <button type="button" class="um-icon-btn um-confirm-trigger" title="Unlock account">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4.5" y="9.5" width="11" height="8" rx="1.5"/><path d="M7 9.5V6a3 3 0 016 0v1.5"/></svg>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('user-management.edit', $user->id) }}" class="um-icon-btn" title="Edit user">
                                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.5 3.5l3 3L7 16H4v-3z"/></svg>
                                </a>
                                <form action="{{ route('user-management.reset-password', $user->id) }}" method="POST" class="um-action-form" data-type="reset" data-name="{{ $user->name }}">
                                    @csrf
                                    <button type="button" class="um-icon-btn um-confirm-trigger" title="Reset password">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="13" r="3"/><path d="M9.2 10.8L15.5 4.5M13.5 6.5l2 2M15.5 4.5l2 2"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="um-action-form" data-type="delete" data-name="{{ $user->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="um-icon-btn danger um-confirm-trigger" title="Delete user">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="um-empty">No users found. Click "Add User" to create a new account.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        window.umFlash = {
            status: @json(session('status')),
            error: @json(session('error')),
            validationError: @json(isset($errors) && $errors->any() ? $errors->first() : null),
        };
    </script>
    <script src="{{ asset('js/user-management.js') }}?v={{ time() }}"></script>

@endsection
