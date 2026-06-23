@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Dashboard" />

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Items</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">
                {{ $totalItems }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                All items registered in inventory.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Categories</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">
                {{ $totalCategories }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Item categories available.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stock Quantity</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">
                {{ $totalStock }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Total quantity from all items.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Transactions</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">
                {{ $totalTransactions }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Stock in and stock out records.
            </p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Items</p>
            <h3 class="mt-2 text-3xl font-semibold text-green-600 dark:text-green-400">
                {{ $availableItems }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Items with quantity more than zero.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Out of Stock Items</p>
            <h3 class="mt-2 text-3xl font-semibold text-red-600 dark:text-red-400">
                {{ $outOfStockCount }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Items with zero quantity.
            </p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Recent Stock Transactions
                </h3>

                <a href="{{ route('stock_transactions.index') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    View All
                </a>
            </div>

            @if($recentTransactions->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No stock transactions found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Item</th>
                                <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">Type</th>
                                <th class="py-3 text-center text-sm font-semibold text-gray-600 dark:text-gray-300">Qty</th>
                                <th class="py-3 text-center text-sm font-semibold text-gray-600 dark:text-gray-300">Created By</th>
                                <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($recentTransactions as $transaction)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $transaction->item->name ?? '-' }}
                                    </td>

                                    <td class="py-3 text-sm">
                                        @if($transaction->type === 'stock_in')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                Stock In
                                            </span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                                Stock Out
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 text-center text-sm text-gray-700 dark:text-gray-300">
                                        {{ $transaction->quantity }}
                                    </td>

                                    <td class="py-3 text-center text-sm text-gray-700 dark:text-gray-300">
                                        {{ $transaction->user->name ?? '-' }}
                                    </td>

                                    <td class="py-3 text-right text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Out of Stock Items
                </h3>

                <a href="{{ route('items.index') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    View Items
                </a>
            </div>

            @if($outOfStockItems->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No out of stock items.</p>
            @else
                <div class="space-y-3">
                    @foreach($outOfStockItems as $item)
                        <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $item->name }}
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->category?->name ?? 'No category' }}
                                </p>
                            </div>

                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                Qty: {{ $item->quantity }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection