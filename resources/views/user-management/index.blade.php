@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('header-actions')
    <button class="bg-blue-600 text-white text-sm px-3 py-2 rounded-md hover:bg-blue-700">+ Tambah User</button>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
        <div class="flex justify-between items-center p-5">
            <h3 class="font-semibold text-gray-900 dark:text-white">User Management</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="text-gray-400 dark:text-gray-500 border-t border-gray-200 dark:border-gray-800">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">Role</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-left px-5 py-3">Last Login</th>
                    <th class="text-left px-5 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t border-gray-200 dark:border-gray-800">
                    <td class="px-5 py-3 text-gray-900 dark:text-gray-200">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs bg-blue-100 dark:bg-blue-600/20 text-blue-700 dark:text-blue-400 px-2 py-1 rounded-full">{{ $user->role }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs bg-green-100 dark:bg-green-600/20 text-green-700 dark:text-green-400 px-2 py-1 rounded-full">Active</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                        {{ $user->last_login ? $user->last_login->format('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-3 flex gap-2">
                        <button class="p-1.5 bg-gray-100 dark:bg-gray-800 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">✎</button>
                        <button class="p-1.5 bg-orange-100 dark:bg-orange-700 text-orange-700 dark:text-white rounded-md hover:bg-orange-200 dark:hover:bg-orange-600">🔑</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection