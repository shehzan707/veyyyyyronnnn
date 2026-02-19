<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "DETAILED WOMEN CATEGORY & PRODUCT ANALYSIS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Get Women category and hierarchy
    $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE name = 'Women'");
    $stmt->execute();
    $womenCat = $stmt->fetch(PDO::FETCH_ASSOC);
    $womenId = $womenCat['id'];
    
    echo "Women Category ID: $womenId\n";
    echo str_repeat("-", 100) . "\n";
    
    // Get all Women descendants
    function getAllDescendantIds($pdo, $parentId, $level = 0) {
        $ids = [];
        $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = :parent_id ORDER BY name");
        $stmt->execute([':parent_id' => $parentId]);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo str_repeat("  ", $level) . "├─ ID: {$row['id']}, Name: {$row['name']}\n";
            $ids[] = $row['id'];
            $descendants = getAllDescendantIds($pdo, $row['id'], $level + 1);
            $ids = array_merge($ids, $descendants);
        }
        
        return $ids;
    }
    
    echo "Women Category Hierarchy:\n";
    $descendantIds = getAllDescendantIds($pdo, $womenId);
    $allWomenIds = array_merge([$womenId], $descendantIds);
    
    echo "\nTotal Women category IDs: " . count($allWomenIds) . "\n";
    echo "Category IDs: " . implode(", ", $allWomenIds) . "\n\n";
    
    // Now check products for Women
    echo str_repeat("=", 100) . "\n";
    echo "WOMEN PRODUCTS CHECK\n";
    echo str_repeat("=", 100) . "\n\n";
    
    $placeholders = implode(",", array_fill(0, count($allWomenIds), "?"));
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM admin_products 
        WHERE category_id IN ($placeholders)
    ");
    $stmt->execute($allWomenIds);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total Women Products: " . $result['count'] . "\n\n";
    
    // Get products by each category
    echo "PRODUCTS BY CATEGORY:\n";
    echo str_repeat("-", 100) . "\n";
    
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, COUNT(p.id) as product_count
        FROM categories c
        LEFT JOIN admin_products p ON c.id = p.category_id
        WHERE c.id IN (" . implode(",", array_fill(0, count($allWomenIds), "?")) . ")
        GROUP BY c.id, c.name
        ORDER BY c.name
    ");
    $stmt->execute($allWomenIds);
    
    $categoryProductCounts = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoryProductCounts[$row['id']] = $row;
        echo "ID: {$row['id']}, Name: {$row['name']}, Products: {$row['product_count']}\n";
    }
    
    echo "\n";
    
    // Check specifically for Crop Tops
    echo str_repeat("=", 100) . "\n";
    echo "CROP TOPS CATEGORY CHECK\n";
    echo str_repeat("=", 100) . "\n\n";
    
    $stmt = $pdo->prepare("SELECT id, name, parent_id FROM categories WHERE name = 'Crop Tops'");
    $stmt->execute();
    $cropTops = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cropTops) {
        echo "Crop Tops Category ID: " . $cropTops['id'] . "\n";
        echo "Crop Tops Parent ID: " . $cropTops['parent_id'] . "\n";
        
        // Get parent name
        if ($cropTops['parent_id']) {
            $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
            $stmt->execute([':id' => $cropTops['parent_id']]);
            $parent = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Crop Tops Parent Category: " . $parent['name'] . "\n";
        }
        
        // Check products in Crop Tops
        $stmt = $pdo->prepare("
            SELECT id, name, category_id, price FROM admin_products 
            WHERE category_id = :cat_id
            LIMIT 20
        ");
        $stmt->execute([':cat_id' => $cropTops['id']]);
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nProducts in Crop Tops (" . count($products) . " found):\n";
        echo str_repeat("-", 100) . "\n";
        
        foreach ($products as $product) {
            echo "ID: {$product['id']}, Name: {$product['name']}, Category ID: {$product['category_id']}\n";
        }
    } else {
        echo "❌ Crop Tops category NOT FOUND!\n";
    }
    
    echo "\n";
    
    // Check for Women category assignment issues
    echo str_repeat("=", 100) . "\n";
    echo "CHECKING FOR WOMEN PRODUCTS WITH WRONG CATEGORY_ID\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Find products that have women-related names but wrong category
    $stmt = $pdo->prepare("
        SELECT id, name, category_id, price
        FROM admin_products
        WHERE (name LIKE '%Women%' OR name LIKE '%crops%' OR name LIKE '%dress%' OR name LIKE '%skirt%' OR name LIKE '%heel%')
        AND category_id NOT IN (" . implode(",", array_fill(0, count($allWomenIds), "?")) . ")
        LIMIT 20
    ");
    $stmt->execute($allWomenIds);
    
    $misassignedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($misassignedProducts) > 0) {
        echo "⚠️  FOUND MISASSIGNED PRODUCTS:\n";
        echo str_repeat("-", 100) . "\n";
        foreach ($misassignedProducts as $product) {
            echo "ID: {$product['id']}, Name: {$product['name']}, Category ID: {$product['category_id']}\n";
        }
    } else {
        echo "✅ No obvious misassignments found\n";
    }
    
    echo "\n";
    
    // List all products in Women categories to find the problematic ones
    echo str_repeat("=", 100) . "\n";
    echo "ALL WOMEN PRODUCTS (to identify problem products)\n";
    echo str_repeat("=", 100) . "\n\n";
    
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.category_id, c.name as category_name
        FROM admin_products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.category_id IN (" . implode(",", array_fill(0, count($allWomenIds), "?")) . ")
        ORDER BY p.id
    ");
    $stmt->execute($allWomenIds);
    
    $allWomenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total Women Products Listed: " . count($allWomenProducts) . "\n\n";
    
    // Show them grouped by category
    $byCategory = [];
    foreach ($allWomenProducts as $product) {
        $catName = $product['category_name'] ?? 'UNKNOWN';
        if (!isset($byCategory[$catName])) {
            $byCategory[$catName] = [];
        }
        $byCategory[$catName][] = $product;
    }
    
    foreach ($byCategory as $catName => $products) {
        echo "📁 {$catName} (" . count($products) . " products)\n";
        foreach (array_slice($products, 0, 5) as $product) {
            echo "   - {$product['name']}\n";
        }
        if (count($products) > 5) {
            echo "   ... and " . (count($products) - 5) . " more\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
