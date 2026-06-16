<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category',
        'quantity',
        'price',
        'description',
    ];

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}