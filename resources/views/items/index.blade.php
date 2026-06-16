@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Items" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h3 class="mb-2 font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                    Items List
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all inventory items and stock quantity.
                </p>
            </div>

            <a href="{{ route('items.create') }}"
               class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Add New Item
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($items->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400">No items found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-left">Name</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 dark:text-gray-300">Category</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 dark:text-gray-300">Description</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 dark:text-gray-300">Quantity</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-left">Price</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-left">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $item->name }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $item->category?->name ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $item->description ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $item->quantity }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">RM {{ number_format($item->price, 2) }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @if($item->quantity <= 0)
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                            Out of Stock
                                        </span>
                                    @else
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            Available
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 border border-gray-300 py-3 text-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('items.edit', $item) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                                            Edit
                                        </a>

                                        <form action="{{ route('items.destroy', $item) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection