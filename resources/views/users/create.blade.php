@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Create User Account
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            This page is only accessible by admin.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}"
          class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        @csrf

        <div class="mb-5">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Name
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
        </div>

        <div class="mb-5">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Email
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
        </div>

        <div class="mb-5">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Role
            </label>
            <select
                name="role"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-5">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Password
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
        </div>

        <div class="mb-6">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Confirm Password
            </label>
            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
            >
                Create Account
            </button>
        </div>
    </form>
</div>
@endsection