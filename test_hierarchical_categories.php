<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Category;
use JSON;

// Simulate what the ProductController will generate
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
            // Check if this child has its own children (sub-categories)
            $grandchildren = $child->children()->withCount('products')->orderBy('name')->get();
            
            if ($grandchildren->count() > 0) {
                // This is a grouping/header category (like "Apparel", "Bottoms")
                $grouped[] = [
                    'name' => $child->name,
                    'count' => 0,
                    'isHeader' => true,
                    'children' => $grandchildren->map(function($cat) {
                        return [
                            'name' => $cat->name,
                            'count' => $cat->products_count
                        ];
                    })->toArray()
                ];
            } else {
                // Direct filterable category (like "Sneakers", "Casual Shoes")
                $childProducts = $child->products()->count();
                $grouped[] = [
                    'name' => $child->name,
                    'count' => $childProducts,
                    'isHeader' => false,
                    'children' => []
                ];
            }
        }
        
        $categoryGroups[$genderKey] = $grouped;
    }
}

echo "Men Categories Structure:\n";
echo json_encode($categoryGroups['men'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
