<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$db = $app->make('db');

echo "\n\n====== CATEGORIES HIERARCHY ======\n\n";

// Get root categories
$root = $db->select('SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY id');

foreach ($root as $r) {
    echo "✓ ROOT: {$r->name} (ID: {$r->id})\n";
    
    // Get sub-categories
    $subs = $db->select('SELECT id, name FROM categories WHERE parent_id = ? ORDER BY id', [$r->id]);
    foreach ($subs as $sub) {
        echo "    ├─ {$sub->name} (ID: {$sub->id})\n";
        
        // Get sub-sub-categories
        $subsubs = $db->select('SELECT id, name FROM categories WHERE parent_id = ? ORDER BY id', [$sub->id]);
        foreach ($subsubs as $subsub) {
            echo "    │  └─ {$subsub->name}\n";
        }
    }
    echo "\n";
}

$total = $db->select('SELECT COUNT(*) as cnt FROM categories')[0]->cnt;
echo "\n=== TOTAL: {$total} CATEGORIES ===\n\n";
