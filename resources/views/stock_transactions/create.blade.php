@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Create Stock Transaction" />

    <div class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <h3 class="mb-6 font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
            Create Stock Transaction
        </h3>

        <form action="{{ route('stock_transactions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="item_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item</label>
                <select name="item_id" id="item_id"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Select item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} — Current stock: {{ $item->quantity }}
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Type</label>
                <select name="type" id="type"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">Select type</option>
                    <option value="stock_in" {{ old('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                    <option value="stock_out" {{ old('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
                <textarea name="note" id="note" rows="4"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('note') }}</textarea>
                @error('note')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Save Transaction
                </button>

                <a href="{{ route('stock_transactions.index') }}" class="ml-4 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection