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
        $query = Product::with(['categoryModel' => function($query) {
            $query->with('parent');
        }]);

        // Gender/Type filter mapping to specific categories
        if ($request->filled('gender')) {
            $gender = $request->gender;
            $categoryIds = [];
            
            if ($gender === 'men') {
                // Men's specific categories
                $categoryIds = Category::whereIn('name', 
                    ['Formal Shirts', 'Casual Shirt', 'Jackets', 'T shirt', 'Blazers', 'Suits', 
                     'Overcoats', 'Sweatshirt', 'Denim', 'Sweatpant', 'Trouser', 'Shorts']
                )->pluck('id')->toArray();
            } elseif ($gender === 'women') {
                // Women's specific categories
                $categoryIds = Category::whereIn('name',
                    ['Tops', 'Shirts', 'Dress', 'Sweatshirts', 'Crop tops', 'Trousers', 
                     'Jeans', 'Long Skirts', 'Sweatbottoms', 'Half Skirts']
                )->pluck('id')->toArray();
            } elseif ($gender === 'accessories') {
                // Accessories specific categories
                $categoryIds = Category::whereIn('name',
                    ['Wallets', 'Belts', 'Sunglasses', 'Caps', 'Rings', 'Bracelets', 
                     'Handbags', 'Backpacks', 'Leather Strap', 'Chain Strap']
                )->pluck('id')->toArray();
            } elseif ($gender === 'footwear') {
                // Footwear specific categories
                $categoryIds = Category::whereIn('name',
                    ['Casual Shoes', 'Sneakers', 'Formal Shoes', 'Slides', 'Heels', 
                     'casual boots', 'Sneaker', 'Sandles']
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

        // Search filter - match whole words, not substrings
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            // Use word boundary matching to avoid "men" matching "women"
            $query->where(function($q) use ($searchTerm) {
                // Match as separate word or exact phrase
                $q->where('name', 'like', $searchTerm . '%')  // Start of name
                  ->orWhere('name', 'like', '% ' . $searchTerm . '%')  // After space
                  ->orWhere('name', '=', $searchTerm);  // Exact match
            });
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
        $product = Product::with(['categoryModel' => function($query) {
            $query->with('parent');
        }])->findOrFail($id);
        return view('shop.product-detail', compact('product'));
    }
}

