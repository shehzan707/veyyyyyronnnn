<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n====== VEYRON CATEGORY HIERARCHY ======\n\n";

$rootCategories = \App\Models\Category::whereNull('parent_id')->orderBy('id')->get();

foreach ($rootCategories as $root) {
    echo "✓ " . strtoupper($root->name) . " (ID: {$root->id})\n";
    
    $children = $root->children()->orderBy('id')->get();
    $lastChild = count($children) - 1;
    
    foreach ($children as $idx => $child) {
        $isLast = $idx === $lastChild;
        $prefix = $isLast ? '└── ' : '├── ';
        echo "  " . $prefix . $child->name . "\n";
        
        $grandchildren = $child->children()->orderBy('id')->get();
        $lastGrandchild = count($grandchildren) - 1;
        
        foreach ($grandchildren as $gidx => $grandchild) {
            $isLastGrand = $gidx === $lastGrandchild;
            $gprefix = $isLast ? '     ' : '  │  ';
            $gsuffix = $isLastGrand ? '└── ' : '├── ';
            echo $gprefix . $gsuffix . $grandchild->name . "\n";
        }
    }
    echo "\n";
}

$total = \App\Models\Category::count();
echo "=== TOTAL: $total CATEGORIES ===\n\n";
