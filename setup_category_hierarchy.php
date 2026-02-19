<?php

$path = __DIR__;
require $path . '/vendor/autoload.php';
$app = require_once $path . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

echo "Setting up category hierarchy...\n\n";

// Create parent categories
$parents = ['Men', 'Women', 'Accessories', 'Footwear'];
$parentIds = [];

foreach ($parents as $parentName) {
    $parent = Category::firstOrCreate(
        ['name' => $parentName],
        ['parent_id' => null, 'slug' => strtolower($parentName)]
    );
    $parentIds[$parentName] = $parent->id;
    echo "✓ Parent: $parentName (ID: {$parent->id})\n";
}

echo "\nAssigning categories to parents...\n";

// Define category mappings
$mappings = [
    'Men' => [
        'Casual Shirts', 'T Shirt', 'Knitwear', 'Jackets', 'Blazers', 'Suits',
        'Formal Shirts', 'Overcoats', 'Shirts', 'Trousers', 'Denim', 'Shorts', 'Sweatpants'
    ],
    'Women' => [
        'Tops', 'Dresses', 'Sweaters', 'Crop Tops', 'Jeans', 'Long Skirts',
        'Sweatbottoms', 'Half Skirts'
    ],
    'Accessories' => [
        'Leather Strap', 'Metal Strap', 'Sunglasses', 'Caps', 'Belts', 'Wallets',
        'Backpacks', 'Handbags', 'Rings', 'Bracelets'
    ],
    'Footwear' => [
        'Heels', 'Casual Shoes', 'Sneaker', 'Sandals', 'Sneakers'
    ]
];

// Assign categories to parents
$count = 0;
foreach ($mappings as $parentName => $childNames) {
    echo "\n$parentName:\n";
    foreach ($childNames as $childName) {
        $child = Category::where('name', $childName)->first();
        if ($child) {
            $child->update(['parent_id' => $parentIds[$parentName]]);
            $count++;
            echo "  ✓ $childName\n";
        } else {
            echo "  ✗ $childName (not found)\n";
        }
    }
}

echo "\n\nSetup complete! Updated $count categories.\n";
