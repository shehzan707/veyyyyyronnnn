<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

echo "=== Verifying Child Categories ===\n\n";

$genderMap = [
    'Men' => 'men',
    'Women' => 'women',
    'Accessories' => 'accessories',
    'Footwear' => 'footwear'
];

foreach ($genderMap as $parentName => $genderKey) {
    $parent = Category::where('name', $parentName)->first();
    if ($parent) {
        echo "$parentName (ID: {$parent->id}):\n";
        $children = $parent->children()
            ->withCount('products')
            ->orderBy('name')
            ->get();
        
        echo "  Total children: " . count($children) . "\n";
        foreach ($children as $child) {
            echo "    - {$child->name} (" . $child->products_count . " products)\n";
        }
        echo "\n";
    }
}

echo "Done!\n";
