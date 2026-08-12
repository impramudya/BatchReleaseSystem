@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

     <link rel="stylesheet" href="{{ asset('css/user-management.css') }}">

    <div id="um-toast" class="um-toast">
        <svg id="um-toast-icon" class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"></svg>
        <span id="um-toast-text"></span>
    </div>

    <div id="um-modal-overlay" class="um-modal-overlay">
        <div class="um-modal" role="alertdialog" aria-modal="true" aria-labelledby="um-modal-title">
            <div class="um-modal-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" width="19" height="19">
                    <path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="um-modal-title" id="um-modal-title">Hapus pengguna ini?</h3>
            <p class="um-modal-text">
                <strong id="um-modal-name"></strong> akan kehilangan akses sepenuhnya. Data yang sudah dihapus tidak bisa dikembalikan.
            </p>
            <div class="um-modal-actions">
                <button type="button" class="um-modal-cancel" id="um-modal-cancel">Batal</button>
                <button type="button" class="um-modal-confirm" id="um-modal-confirm">Hapus pengguna</button>
            </div>
        </div>
    </div>

    <div class="um-card">
        <div class="um-card-head">
            <div>
                <h3 class="um-card-title">Daftar Pengguna</h3>
                <p class="um-card-sub">{{ $users->total() }} akun terdaftar di sistem QA</p>
            </div>
            <div class="um-head-right">
                <div class="um-search">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M16 16l-3.3-3.3"/></svg>
                    <input type="text" id="um-search-input" placeholder="Cari nama atau email...">
                </div>
                <a href="{{ route('user-management.create') }}" class="um-add-btn">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 4.5v11M4.5 10h11"/></svg>
                    Tambah User
                </a>
            </div>
        </div>

        <table class="um-table">
            <thead>
                <tr>
                    <th>Nama</th>
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
                            <div class="um-person">
                                <div class="um-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <span class="um-name">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="um-email">{{ $user->email }}</td>
                        <td><span class="um-role">{{ $user->role }}</span></td>
                        <td>
                            @if(($user->status ?? 'active') === 'active')
                                <span class="um-status"><span class="dot"></span> Aktif</span>
                            @else
                                <span class="um-status inactive"><span class="dot"></span> Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="um-actions">
                                <a href="{{ route('user-management.edit', $user->id) }}" class="um-icon-btn" title="Edit pengguna">
                                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.5 3.5l3 3L7 16H4v-3z"/></svg>
                                </a>
                                <form action="{{ route('user-management.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Reset password {{ $user->name }} dengan password sementara?');">
                                    @csrf
                                    <button type="submit" class="um-icon-btn" title="Reset password">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="13" r="3"/><path d="M9.2 10.8L15.5 4.5M13.5 6.5l2 2M15.5 4.5l2 2"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="um-delete-form" data-name="{{ $user->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="um-icon-btn danger um-delete-trigger" title="Hapus pengguna">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="um-empty">Belum ada pengguna. Klik "Tambah User" untuk menambahkan akun baru.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (method_exists($users, 'links'))
            <div class="um-pagination">{{ $users->links() }}</div>
        @endif
    </div>

<script>
        window.umFlash = {
            status: @json(session('status')),
            error: @json(session('error')),
            validationError: @json($errors->any() ? $errors->first() : null),
        };
    </script>
    <script src="{{ asset('js/user-management.js') }}"></script>

@endsection
