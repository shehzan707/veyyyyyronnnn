<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['product_id', 'size', 'stock', 'is_available'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function isLowStock()
    {
        return $this->stock <= 2 && $this->stock > 0;
    }

    public function isOutOfStock()
    {
        return $this->stock == 0;
    }
}
