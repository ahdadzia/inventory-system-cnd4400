@extends('layouts.fullscreen-layout')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 dark:bg-gray-900">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-lg dark:bg-gray-800">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Inventory System Login
            </h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Sign in using the account created by the admin.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    placeholder="admin@example.com"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    placeholder="Enter your password"
                >
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        class="rounded border-gray-300"
                    >
                    Remember me
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600"
            >
                Sign In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            No public registration. Only admin can create user accounts.
        </p>
    </div>
</div>
@endsection