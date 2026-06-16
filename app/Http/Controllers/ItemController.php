<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::latest()->get();
        $categories = Category::get();

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item, Category $category)
    {
        return view('items.edit', compact('item', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item, Category $category)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
