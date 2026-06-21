@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Stock Transactions" />

    <div class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="mb-6 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                Stock Transactions
            </h3>

            <a href="{{ route('stock_transactions.create') }}"
               class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Add Transaction
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($stockTransactions->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400">No stock transactions found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th class="border border-gray-300 px-4 py-2 dark:border-gray-700">ID</th>
                            <th class="border border-gray-300 px-4 py-2 dark:border-gray-700">Item</th>
                            <th class="border border-gray-300 px-4 py-2 dark:border-gray-700">Type</th>
                            <th class="border border-gray-300 px-4 py-2 dark:border-gray-700">Quantity</th>
                            <th class="border border-gray-300 px-4 py-2 dark:border-gray-700">Note</th>
                            <th class="border border-gray-300 px-4 py-2 dark:border-gray-700">Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($stockTransactions as $transaction)
                            <tr class="text-center dark:text-gray-300">
                                <td class="border border-gray-300 px-4 py-2 dark:border-gray-700">
                                    {{ $transaction->id }}
                                </td>

                                <td class="border border-gray-300 px-4 py-2 dark:border-gray-700">
                                    {{ $transaction->item->name ?? '-' }}
                                </td>

                                <td class="border border-gray-300 px-4 py-2 dark:border-gray-700">
                                    @if($transaction->type === 'stock_in')
                                        <span class="rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            Stock In
                                        </span>
                                    @else
                                        <span class="rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                            Stock Out
                                        </span>
                                    @endif
                                </td>

                                <td class="border border-gray-300 px-4 py-2 dark:border-gray-700">
                                    {{ $transaction->quantity }}
                                </td>

                                <td class="border border-gray-300 px-4 py-2 dark:border-gray-700">
                                    {{ $transaction->note ?? '-' }}
                                </td>

                                <td class="border border-gray-300 px-4 py-2 dark:border-gray-700">
                                    {{ $transaction->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection