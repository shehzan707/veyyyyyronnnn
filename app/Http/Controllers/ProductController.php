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

        // Gender/Type filter - filter by parent category (including all descendants)
        if ($request->filled('gender')) {
            $gender = $request->gender;
            
            // Map gender string to parent category name
            $genderMap = [
                'men' => 'Men',
                'women' => 'Women',
                'accessories' => 'Accessories',
                'footwear' => 'Footwear'
            ];
            
            $parentName = $genderMap[$gender] ?? null;
            
            if ($parentName) {
                // Get parent category
                $parentCategory = Category::where('name', $parentName)->first();
                
                if ($parentCategory) {
                    // Get ALL descendant categories recursively (not just direct children)
                    $descendantIds = $this->getAllDescendantCategoryIds($parentCategory->id);
                    $descendantIds[] = $parentCategory->id; // Include the parent itself
                    
                    if (!empty($descendantIds)) {
                        $query->whereIn('category_id', $descendantIds);
                    }
                }
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
            // Try to find by slug first, then by name for backward compatibility
            $category = Category::where('slug', $request->category)->first();
            if (!$category) {
                $category = Category::where('name', $request->category)->first();
            }
            if ($category) {
                // Get the category and all its descendants
                $descendantIds = $this->getAllDescendantCategoryIds($category->id);
                $descendantIds[] = $category->id;
                
                if (!empty($descendantIds)) {
                    $query->whereIn('category_id', $descendantIds);
                }
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

        $products = $query->with(['categoryModel', 'categoryModel.parent'])->orderBy('id', 'desc')->get();

        // Return JSON for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'products' => $products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => asset($product->image),
                        'category' => $product->categoryModel->name ?? $product->category,
                    ];
                })
            ]);
        }

        // Get all categories with product counts, organized by gender/parent category with hierarchy
        $categoryGroups = [];
        $genderMap = [
            'Men' => 'men',
            'Women' => 'women',
            'Accessories' => 'accessories',
            'Footwear' => 'footwear'
        ];

        foreach ($genderMap as $parentName => $genderKey) {
            $parent = Category::where('name', $parentName)->first();
            if ($parent) {
                $directChildren = $parent->children()->get();
                $grouped = [];
                
                foreach ($directChildren as $child) {
                    // For Women category, skip Footwear and Accessories from sidebar display
                    if ($genderKey === 'women' && in_array($child->name, ['Footwear', 'Accessories'])) {
                        continue;
                    }
                    
                    // Check if this child has its own children (sub-categories)
                    $grandchildren = $child->children()->withCount('products')->orderBy('name')->get();
                    
                    if ($grandchildren->count() > 0) {
                        // This is a grouping/header category (like "Apparel", "Bottoms")
                        $grouped[] = [
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'count' => 0,
                            'isHeader' => true,
                            'children' => $grandchildren->map(function($cat) {
                                return [
                                    'name' => $cat->name,
                                    'slug' => $cat->slug,
                                    'count' => $cat->products_count
                                ];
                            })->toArray()
                        ];
                    } else {
                        // Direct filterable category (like "Sneakers", "Casual Shoes")
                        $childProducts = $child->products()->count();
                        $grouped[] = [
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'count' => $childProducts,
                            'isHeader' => false,
                            'children' => []
                        ];
                    }
                }
                
                $categoryGroups[$genderKey] = $grouped;
            }
        }

        // Keep for backward compatibility
        $categories = Category::orderBy('name')->get();

        return view('shop.products', compact('products', 'categories', 'categoryGroups'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('shop.product-detail', compact('product'));
    }

    /**
     * Recursively get all descendant category IDs
     */
    private function getAllDescendantCategoryIds($parentId)
    {
        $ids = [];
        
        // Get direct children
        $children = Category::where('parent_id', $parentId)->pluck('id')->toArray();
        
        foreach ($children as $childId) {
            $ids[] = $childId;
            // Recursively get descendants of this child
            $descendants = $this->getAllDescendantCategoryIds($childId);
            $ids = array_merge($ids, $descendants);
        }
        
        return $ids;
    }
}

