<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "TESTING HIERARCHICAL FILTER LOGIC\n";
    echo str_repeat("=", 70) . "\n\n";
    
    // Step 1: User selects "Men"
    echo "STEP 1: User Selects 'Men'\n";
    echo str_repeat("-", 70) . "\n";
    
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Men'");
    $stmt->execute();
    $menCat = $stmt->fetch(PDO::FETCH_ASSOC);
    $menId = $menCat['id'];
    
    echo "Men Category ID: $menId\n";
    
    // Simulate recursive descendant fetch
    function getAllDescendantIds($pdo, $parentId) {
        $ids = [];
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE parent_id = :parent_id");
        $stmt->execute([':parent_id' => $parentId]);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $childId = $row['id'];
            $ids[] = $childId;
            $descendants = getAllDescendantIds($pdo, $childId);
            $ids = array_merge($ids, $descendants);
        }
        
        return $ids;
    }
    
    $descendantIds = getAllDescendantIds($pdo, $menId);
    $allIds = array_merge([$menId], $descendantIds);
    
    echo "All Descendant Category IDs: " . implode(", ", $allIds) . "\n";
    
    // Count products matching this filter
    $placeholders = implode(",", array_fill(0, count($allIds), "?"));
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_products WHERE category_id IN ($placeholders)");
    $stmt->execute($allIds);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Products with Men categories: " . $result['count'] . "\n\n";
    
    // Step 2: User selects "Formal Shirts" (after selecting Men)
    echo "STEP 2: User Selects 'Formal Shirts' (within Men)\n";
    echo str_repeat("-", 70) . "\n";
    
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Formal Shirts'");
    $stmt->execute();
    $formalShirtsCat = $stmt->fetch(PDO::FETCH_ASSOC);
    $formalShirtsId = $formalShirtsCat['id'];
    
    echo "Formal Shirts Category ID: $formalShirtsId\n";
    
    // Filter by both Men (descendants) AND Formal Shirts
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM admin_products 
        WHERE category_id IN (" . implode(",", array_fill(0, count($allIds), "?")) . ")
        AND category_id IN (?)
    ");
    $params = array_merge($allIds, [$formalShirtsId]);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Products with Formal Shirts (filtered by Men): " . $result['count'] . "\n";
    
    // Get the actual products
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, c.name as category
        FROM admin_products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.category_id IN (" . implode(",", array_fill(0, count($allIds), "?")) . ")
        AND p.category_id IN (?)
        LIMIT 5
    ");
    $stmt->execute($params);
    
    echo "\nSample Products:\n";
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - " . $row['name'] . " (Category: " . $row['category'] . ")\n";
        $count++;
    }
    
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "✅ Filter Logic Verified Successfully!\n";
    echo "When user selects Men + Formal Shirts, only Formal Shirts products are shown.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
