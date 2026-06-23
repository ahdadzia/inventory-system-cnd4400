@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-screen-2xl md:p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
            RPC Client
        </h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            This page calls the inventory server using JSON-RPC from the user interface.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- LEFT SIDE: RPC ACTIONS --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                RPC Actions
            </h3>

            <button
                type="button"
                onclick="listItemsRpc()"
                class="mb-6 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
            >
                Load Items using RPC
            </button>

            <hr class="mb-6 border-gray-200 dark:border-gray-800">

            <h4 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90">
                Record Stock Transaction using RPC
            </h4>

            <form id="stockRpcForm" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Item ID
                    </label>
                    <input
                        type="number"
                        id="item_id"
                        min="1"
                        required
                        placeholder="Example: 7"
                        class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    >
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Transaction Type
                    </label>
                    <select
                        id="type"
                        required
                        class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    >
                        <option value="stock_in">Stock In</option>
                        <option value="stock_out">Stock Out</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Quantity
                    </label>
                    <input
                        type="number"
                        id="quantity"
                        min="1"
                        required
                        placeholder="Example: 5"
                        class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    >
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Note
                    </label>
                    <textarea
                        id="note"
                        rows="3"
                        placeholder="Example: Added from RPC UI"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700"
                >
                    Submit Stock RPC
                </button>
            </form>
        </div>

        {{-- RIGHT SIDE: RPC RESPONSE --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                RPC Response
            </h3>

            <pre id="rpcResponse" class="min-h-[300px] overflow-auto rounded-lg bg-gray-900 p-4 text-sm text-green-400">No RPC request sent yet.</pre>
        </div>
    </div>

    {{-- ITEM TABLE --}}
    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
            Items Loaded from RPC
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm dark:border-gray-800">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="border px-4 py-2 text-left dark:text-gray-300 text-gray-700 dark:border-gray-700">ID</th>
                        <th class="border px-4 py-2 text-left dark:text-gray-300 text-gray-700 dark:border-gray-700">Name</th>
                        <th class="border px-4 py-2 text-left dark:text-gray-300 text-gray-700 dark:border-gray-700">Category</th>
                        <th class="border px-4 py-2 text-left dark:text-gray-300 text-gray-700 dark:border-gray-700">Quantity</th>
                        <th class="border px-4 py-2 text-left dark:text-gray-300 text-gray-700 dark:border-gray-700">Price</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <tr>
                        <td colspan="5" class="border px-4 py-4 text-center text-gray-500 dark:border-gray-700">
                            Click "Load Items using RPC"
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function callRpc(method, params = {}, id = Date.now()) {
        const response = await fetch('/api/rpc', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                jsonrpc: '2.0',
                method: method,
                params: params,
                id: id
            })
        });

        const data = await response.json();

        document.getElementById('rpcResponse').textContent = JSON.stringify(data, null, 2);

        return data;
    }

    async function listItemsRpc() {
        const data = await callRpc('items.list', {}, 1);

        const tableBody = document.getElementById('itemsTableBody');
        tableBody.innerHTML = '';

        if (!data.result || data.result.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="border px-4 py-4 text-center text-gray-500 dark:border-gray-700">
                        No items found.
                    </td>
                </tr>
            `;
            return;
        }

        data.result.forEach(item => {
            tableBody.innerHTML += `
                <tr>
                    <td class="border px-4 py-2 dark:text-gray-300 text-gray-700 dark:border-gray-700">${item.id}</td>
                    <td class="border px-4 py-2 dark:text-gray-300 text-gray-700 dark:border-gray-700">${item.name}</td>
                    <td class="border px-4 py-2 dark:text-gray-300 text-gray-700 dark:border-gray-700">${item.category ?? '-'}</td>
                    <td class="border px-4 py-2 dark:text-gray-300 text-gray-700 dark:border-gray-700">${item.quantity}</td>
                    <td class="border px-4 py-2 dark:text-gray-300 text-gray-700 dark:border-gray-700">${item.price}</td>
                </tr>
            `;
        });
    }

    document.getElementById('stockRpcForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const params = {
            item_id: Number(document.getElementById('item_id').value),
            type: document.getElementById('type').value,
            quantity: Number(document.getElementById('quantity').value),
            note: document.getElementById('note').value
        };

        await callRpc('stock.record', params, 2);

        await listItemsRpc();
    });
</script>
@endpush