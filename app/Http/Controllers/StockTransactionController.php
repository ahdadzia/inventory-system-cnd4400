<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockTransactions = StockTransaction::with('item')->latest()->get();
        $title = 'Stock Transactions';
        return view('stock_transactions.index', compact('stockTransactions', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::orderBy('name')->get();
        $title = 'Add New Stock Transaction';
        return view('stock_transactions.create', compact('items', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:stock_in,stock_out',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $item = Item::findOrFail($request->item_id);

            if ($request->type === 'stock_out' && $item->quantity < $request->quantity) {
                throw new \Exception('Insufficient stock for this transaction.');
            }

            // Update item quantity
            $item->quantity += ($request->type === 'stock_in' ? $request->quantity : -$request->quantity);
            $item->save();

            // Create stock transaction
            StockTransaction::create([
                'item_id' => $request->item_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'note' => $request->note,
            ]);
        });

        return redirect()->route('stock_transactions.index')->with('success', 'Stock transaction recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransaction $stockTransaction)
    {
        $title = 'Edit Stock Transaction';
        return view('stock_transactions.edit', compact('stockTransaction', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockTransaction $stockTransaction)
    {
        //
    }
}
