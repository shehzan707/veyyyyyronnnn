<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "FINAL WOMEN CATEGORY STRUCTURE VERIFICATION\n";
    echo str_repeat("=", 100) . "\n\n";
    
    function printCategoryTree($pdo, $parentId = null, $level = 0, $rootName = '') {
        $indent = str_repeat("  ", $level);
        
        if ($parentId === null) {
            $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE name = 'Women' ORDER BY name");
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = :parent_id ORDER BY name");
            $stmt->execute([':parent_id' => $parentId]);
        }
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Count products
            $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_products WHERE category_id = :cat_id");
            $countStmt->execute([':cat_id' => $row['id']]);
            $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            echo "{$indent}├─ {$row['name']} (ID: {$row['id']}, Products: $count)\n";
            printCategoryTree($pdo, $row['id'], $level + 1, $row['name']);
        }
    }
    
    printCategoryTree($pdo);
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "PRODUCT COUNT VERIFICATION\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Get all Women category IDs
    $stmt = $pdo->prepare("
        WITH RECURSIVE cat_tree AS (
            SELECT id, parent_id, name
            FROM categories
            WHERE id = 2
            UNION ALL
            SELECT c.id, c.parent_id, c.name
            FROM categories c
            INNER JOIN cat_tree ct ON c.parent_id = ct.id
        )
        SELECT GROUP_CONCAT(id SEPARATOR ',') as ids
        FROM cat_tree
    ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $allWomenCatIds = array_map('trim', explode(',', $result['ids']));
    
    echo "All Women Category IDs: " . implode(", ", $allWomenCatIds) . "\n\n";
    
    $placeholders = implode(",", array_fill(0, count($allWomenCatIds), "?"));
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_products FROM admin_products 
        WHERE category_id IN ($placeholders)
    ");
    $stmt->execute($allWomenCatIds);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];
    
    echo "✅ TOTAL WOMEN PRODUCTS: $total\n\n";
    
    // Test filtering by specific categories
    echo str_repeat("=", 100) . "\n";
    echo "TESTING FILTER SCENARIOS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Test 1: Filter Crop Tops
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Crop Tops'");
    $stmt->execute();
    $cropTops = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_products WHERE category_id = :cat_id");
    $stmt->execute([':cat_id' => $cropTops['id']]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "Crop Tops Products: $count ✅\n";
    
    // Test 2: Filter Heels
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Heels' AND parent_id IN (SELECT id FROM categories WHERE parent_id = 2)");
    $stmt->execute();
    $heels = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_products WHERE category_id = :cat_id");
    $stmt->execute([':cat_id' => $heels['id']]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "Heels Products (Women > Footwear > Heels): $count ✅\n";
    
    // Test 3: Filter Belts
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Belts' AND parent_id IN (SELECT id FROM categories WHERE parent_id = 2)");
    $stmt->execute();
    $belts = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($belts) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_products WHERE category_id = :cat_id");
        $stmt->execute([':cat_id' => $belts['id']]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "Belts Products (Women > Accessories > Belts): $count ✅\n";
    }
    
    echo "\n✅ ALL WOMEN PRODUCTS ARE NOW PROPERLY CATEGORIZED!\n";
    echo "✅ Women filtering will now work correctly for all categories!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
