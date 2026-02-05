<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Gender/Type filter mapping to categories
        if ($request->filled('gender')) {
            $gender = $request->gender;
            $categoryIds = [];
            
            if ($gender === 'men') {
                // Men's categories: Shirts, Jeans, Tops, T-shirts, etc.
                $categoryIds = Category::whereIn('name', 
                    ['Shirts', 'Jeans', 'Tops', 'Casual Shoes', 'Jackets', 'T-Shirts', 'Formal Shirts', 'Shorts']
                )->pluck('id')->toArray();
            } elseif ($gender === 'women') {
                // Women's categories: Dresses, Sarees, Kurtis, etc.
                $categoryIds = Category::whereIn('name',
                    ['Dresses', 'Tops', 'Jeans', 'Handbags', 'Sarees', 'Kurtis', 'Leggings']
                )->pluck('id')->toArray();
            } elseif ($gender === 'accessories') {
                // Accessories: Watches, Sunglasses, Belts, etc.
                $categoryIds = Category::whereIn('name',
                    ['Watches', 'Sunglasses', 'Belts', 'Scarves', 'Hats', 'Handbags', 'Jewelry']
                )->pluck('id')->toArray();
            } elseif ($gender === 'footwear') {
                // Footwear: Shoes, Sandals, Boots, etc.
                $categoryIds = Category::whereIn('name',
                    ['Casual Shoes', 'Sports Shoes', 'Formal Shoes', 'Sandals', 'Heels', 'Boots', 'Flip Flops']
                )->pluck('id')->toArray();
            }
            
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Multiple category filter
        if ($request->filled('categories')) {
            $categories = $request->input('categories');
            if (is_array($categories) && count($categories) > 0) {
                $categoryIds = Category::whereIn('name', $categories)->pluck('id');
                if ($categoryIds->count() > 0) {
                    $query->whereIn('category_id', $categoryIds);
                }
            }
        }

        // Single category filter (for backward compatibility)
        if ($request->filled('category')) {
            $category = Category::where('name', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search filter - word boundary matching to prevent substring false positives
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            // Use REGEXP to match word boundaries: start of string OR after whitespace
            $query->whereRaw("name REGEXP '^{$searchTerm}|\\s{$searchTerm}'");
        }

        // Price filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->orderBy('id', 'desc')->get();

        // Get categories from database
        $categories = Category::orderBy('name')->get();

        return view('shop.products', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('shop.product-detail', compact('product'));
    }
}

