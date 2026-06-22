@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                User Accounts
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Only admin can create accounts for this inventory system.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Add New User
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Name</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Email</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Role</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t border-gray-100 dark:border-gray-700">
                        <td class="px-5 py-4 text-sm text-gray-800 dark:text-white">
                            {{ $user->name }}
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $user->email }}
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="rounded-full px-3 py-1 text-xs font-medium
                                {{ $user->role === 'admin'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection