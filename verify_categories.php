<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

// Get MEN category
$men = Category::where('slug', 'men')->first();

if ($men) {
    echo "✓ MEN Category Found\n";
    echo "  ID: {$men->id}\n";
    echo "\n  Subcategories:\n";
    
    foreach ($men->children as $subcategory) {
        echo "  • {$subcategory->name} (slug: {$subcategory->slug})\n";
        
        if ($subcategory->children->count() > 0) {
            foreach ($subcategory->children as $item) {
                echo "    - {$item->name}\n";
            }
        }
    }
} else {
    echo "✗ MEN Category not found\n";
}
