<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

try {
    // Get database manager using the actual class
    $db = $app->make('Illuminate\Database\DatabaseManager');
    
    $db->table('categories')->truncate();
    
    $db->table('categories')->insert([
        ['name' => 'MEN', 'slug' => 'men', 'sort_order' => 1, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'WOMEN', 'slug' => 'women', 'sort_order' => 2, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'ACCESSORIES', 'slug' => 'accessories', 'sort_order' => 3, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'FOOTWEAR', 'slug' => 'footwear', 'sort_order' => 4, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ]);
    
    echo "✅ Categories Restored!\n";
    
    $cats = $db->table('categories')->orderBy('sort_order')->get();
    foreach ($cats as $cat) {
        echo "  • {$cat->name}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
