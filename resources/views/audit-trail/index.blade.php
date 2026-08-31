@extends('layouts.app')

@section('title', 'Audit Trail - BatchReleasePro')
@section('page-title', 'Audit Trail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}">
    <link rel="stylesheet" href="{{ asset('css/audit-trail.css') }}">
@endpush

@section('content')

    <div class="brp-card">
        <div class="brp-card-header">
            <h3>Audit Trail</h3>
            <div class="brp-header-actions">
                <a href="{{ route('audit-trail.export-excel', request()->query()) }}" class="brp-btn-outline">Export Excel</a>
                <a href="{{ route('audit-trail.export-pdf', request()->query()) }}" class="brp-btn-outline">Export PDF</a>
            </div>
        </div>

        <form method="GET" action="{{ route('audit-trail.index') }}" class="brp-filter-bar">
            <input type="date" name="date_from" value="{{ $filters['dateFrom'] }}" placeholder="Dari tanggal">
            <input type="date" name="date_to" value="{{ $filters['dateTo'] }}" placeholder="Sampai tanggal">

            <select name="user_id">
                <option value="">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($filters['userId'] == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <select name="action">
                <option value="">Semua Aksi</option>
                <option value="create" @selected($filters['action'] === 'create')>Buat Form</option>
                <option value="approved" @selected($filters['action'] === 'approved')>Approve</option>
                <option value="rejected" @selected($filters['action'] === 'rejected')>Reject</option>
                <option value="login" @selected($filters['action'] === 'login')>Login</option>
                <option value="logout" @selected($filters['action'] === 'logout')>Logout</option>
            </select>

            <button type="submit" class="brp-btn-primary">Filter</button>
            <a href="{{ route('audit-trail.index') }}" class="brp-btn-outline">Reset</a>
        </form>

        <div class="brp-table-wrap">
            <table class="brp-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="brp-cell-strong">{{ \Illuminate\Support\Carbon::parse($log['time'])->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $log['user'] }}</td>
                            <td>
                                <span class="brp-badge brp-badge-{{ $log['action'] === 'approved' ? 'released' : ($log['action'] === 'logout' ? 'draft' : $log['action']) }}">
                                    {{ $log['action_label'] }}
                                </span>
                            </td>
                            <td>{{ $log['detail'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="brp-empty">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="brp-pagination">
            {{ $logs->links() }}
        </div>
    </div>

@endsection
