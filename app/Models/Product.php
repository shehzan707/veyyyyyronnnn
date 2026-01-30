<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'admin_products';
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'category',
        'image',
        'sizes',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sizeVariants(): HasMany
    {
        return $this->hasMany(Size::class, 'product_id');
    }

    public function getTotalStock()
    {
        return $this->sizeVariants()->sum('stock');
    }

    public function getStockSummary()
    {
        return $this->sizeVariants()
            ->orderBy('size')
            ->get()
            ->map(fn($size) => [
                'size' => $size->size,
                'stock' => $size->stock,
                'is_low' => $size->isLowStock(),
                'is_out' => $size->isOutOfStock(),
                'is_available' => $size->is_available
            ])
            ->toArray();
    }

    public function canPurchase($size)
    {
        $sizeModel = $this->sizeVariants()->where('size', $size)->first();
        return $sizeModel && $sizeModel->stock > 0 && $sizeModel->is_available;
    }

    public function reduceStock($size, $quantity = 1)
    {
        $sizeModel = $this->sizeVariants()->where('size', $size)->first();
        if ($sizeModel && $sizeModel->stock >= $quantity) {
            $sizeModel->decrement('stock', $quantity);
            return true;
        }
        return false;
    }
}
