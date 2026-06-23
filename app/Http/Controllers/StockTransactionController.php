<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    public function index()
    {
        $stockTransactions = StockTransaction::with(['item', 'user'])
            ->latest()
            ->get();

        $title = 'Stock Transactions';

        return view('stock_transactions.index', compact('stockTransactions', 'title'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();

        $title = 'Add New Stock Transaction';

        return view('stock_transactions.create', compact('items', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:stock_in,stock_out',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $item = Item::findOrFail($request->item_id);

                if ($request->type === 'stock_out' && $item->quantity < $request->quantity) {
                    throw new \Exception('Insufficient stock for this transaction.');
                }

                // Update item quantity
                $item->quantity += ($request->type === 'stock_in'
                    ? $request->quantity
                    : -$request->quantity
                );

                $item->save();

                // Create stock transaction and record the logged-in user
                StockTransaction::create([
                    'item_id' => $request->item_id,
                    'user_id' => auth()->id(),
                    'type' => $request->type,
                    'quantity' => $request->quantity,
                    'note' => $request->note,
                ]);
            });

            return redirect()
                ->route('stock_transactions.index')
                ->with('success', 'Stock transaction recorded successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function show(StockTransaction $stockTransaction)
    {
        //
    }

    public function edit(StockTransaction $stockTransaction)
    {
        $title = 'Edit Stock Transaction';

        return view('stock_transactions.edit', compact('stockTransaction', 'title'));
    }

    public function update(Request $request, StockTransaction $stockTransaction)
    {
        //
    }

    public function destroy(StockTransaction $stockTransaction)
    {
        //
    }
}