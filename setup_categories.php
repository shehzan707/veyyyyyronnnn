<?php

use App\Models\Category;

// Create parent categories if they don't exist
$parents = ['Men', 'Women', 'Accessories', 'Footwear'];
$parentIds = [];

foreach ($parents as $parentName) {
    $parent = Category::firstOrCreate(
        ['name' => $parentName],
        ['parent_id' => null, 'slug' => strtolower($parentName)]
    );
    $parentIds[$parentName] = $parent->id;
}

// Get existing categories
$existingCats = Category::where('parent_id', null)
    ->whereNotIn('name', $parents)
    ->get();

// Category mapping
$mappings = [
    'Men' => ['Casual Shirts', 'T Shirt', 'Knitwear', 'Jackets', 'Blazers', 'Suits', 'Formal Shirts', 'Overcoats', 'Shirts', 'Trousers', 'Denim', 'Shorts', 'Sweatpants'],
    'Women' => ['Tops', 'Dresses', 'Sweaters', 'Crop Tops', 'Jeans', 'Long Skirts', 'Sweatbottoms', 'Half Skirts'],
    'Accessories' => ['Leather Strap', 'Metal Strap', 'Sunglasses', 'Caps', 'Belts', 'Wallets', 'Backpacks', 'Handbags', 'Rings', 'Bracelets'],
    'Footwear' => ['Heels', 'Casual Shoes', 'Sneaker', 'Sandals', 'Sneakers']
];

// Assign categories to parents
foreach ($mappings as $parentName => $childNames) {
    foreach ($childNames as $childName) {
        $child = Category::where('name', $childName)->first();
        if ($child) {
            $child->update(['parent_id' => $parentIds[$parentName]]);
            echo "Updated: $childName -> $parentName\n";
        }
    }
}

echo "\nDone! Parent categories set up.\n";
