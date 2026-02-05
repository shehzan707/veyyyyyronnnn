<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id', // REQUIRED for sub-category system
        'sort_order',
    ];

    /**
     * Parent category (Men -> Shirts)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Child categories (Men -> Shirts -> Casual)
     * Recursive for unlimited depth
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
                    ->with('children');
    }

    /**
     * Products under this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('admin_products')->onDelete('cascade');
            $table->string('size')->comment('XS, S, M, L, XL, etc');
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['product_id', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
?>