<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockTransactions = StockTransaction::latest()->get();
        return view('stock_transactions.index', compact('stockTransactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stock_transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'transaction_type' => 'required|in:in,out',
            'description' => 'nullable|string',
        ]);

        StockTransaction::create($request->all());

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
        //
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
