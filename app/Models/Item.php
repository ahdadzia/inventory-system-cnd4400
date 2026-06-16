<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockTransaction;
use App\Models\Category;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'quantity',
        'price',
        'description',
    ];

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');       
    }
}