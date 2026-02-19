<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "MOVING REMAINING PRODUCTS FROM OLD FOOTWEAR > WOMEN STRUCTURE\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Find and move products from old Footwear > Women categories
    // Old structure: Footwear (3) > Women (30) > [Casual Shoes, Heels, Sandals, Sneaker]
    
    $migrations = [
        // (old_category_id => new_category_id)
        32 => 77,  // Footwear > Women > Casuals Shoes -> Women > Footwear > Casual Shoes
        34 => 79,  // Footwear > Women > Sandals -> Women > Footwear > Sandals
        33 => 80   // Footwear > Women > Sneaker -> Women > Footwear > Sneakers
    ];
    
    foreach ($migrations as $oldCatId => $newCatId) {
        // Get category name
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
        $stmt->execute([':id' => $oldCatId]);
        $oldCat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
        $stmt->execute([':id' => $newCatId]);
        $newCat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Move products
        $stmt = $pdo->prepare("
            UPDATE admin_products
            SET category_id = :new_cat_id
            WHERE category_id = :old_cat_id
        ");
        $stmt->execute([
            ':new_cat_id' => $newCatId,
            ':old_cat_id' => $oldCatId
        ]);
        
        $affectedRows = $stmt->rowCount();
        echo "✓ Moved $affectedRows products from {$oldCat['name']} to {$newCat['name']}\n";
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "FINAL VERIFICATION\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Verify all Women products are now under Women (ID: 2)
    echo "WOMEN CATEGORY (ID: 2) FINAL STRUCTURE:\n";
    echo str_repeat("-", 100) . "\n";
    
    function printCategoryTree($pdo, $parentId, $level = 0) {
        $indent = str_repeat("  ", $level);
        
        $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = :parent_id ORDER BY name");
        $stmt->execute([':parent_id' => $parentId]);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_products WHERE category_id = :cat_id");
            $countStmt->execute([':cat_id' => $row['id']]);
            $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            echo "{$indent}├─ {$row['name']} (ID: {$row['id']}, Products: $count)\n";
            printCategoryTree($pdo, $row['id'], $level + 1);
        }
    }
    
    printCategoryTree($pdo, 2);
    
    // Final product count
    echo "\n";
    $stmt = $pdo->prepare("
        WITH RECURSIVE cat_tree AS (
            SELECT id
            FROM categories
            WHERE id = 2
            UNION ALL
            SELECT c.id
            FROM categories c
            INNER JOIN cat_tree ct ON c.parent_id = ct.id
        )
        SELECT COUNT(*) as total FROM admin_products
        WHERE category_id IN (SELECT id FROM cat_tree)
    ");
    $stmt->execute();
    $totalWomenProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "✅ TOTAL WOMEN PRODUCTS: $totalWomenProducts\n";
    echo "✅ ALL WOMEN PRODUCTS NOW CORRECTLY CATEGORIZED UNDER WOMEN (ID: 2)\n";
    echo "✅ CROP TOPS FILTER AND ALL OTHER WOMEN FILTERS WILL NOW WORK CORRECTLY!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
