<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

echo "=== Existing Categories ===\n";
$categories = Category::all();
foreach ($categories as $cat) {
    echo "- " . $cat->name . " (ID: " . $cat->id . ", Parent: " . ($cat->parent_id ?? 'None') . ")\n";
}
echo "\nTotal: " . count($categories) . " categories\n";
