<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    // Get Men category
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Men'");
    $stmt->execute();
    $menCat = $stmt->fetch(PDO::FETCH_ASSOC);
    $menId = $menCat['id'];
    
    // Get direct children of Men (grouping categories like Apparel, Bottoms)
    $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = :parent_id ORDER BY name");
    $stmt->execute([':parent_id' => $menId]);
    
    echo "HIERARCHICAL CATEGORY STRUCTURE FOR MEN:\n";
    echo str_repeat("=", 70) . "\n\n";
    
    $groupedCategories = [];
    
    while ($grouping = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $groupName = $grouping['name'];
        $groupId = $grouping['id'];
        
        // Get grandchildren (actual filterable categories)
        $grandchildStmt = $pdo->prepare("
            SELECT c.id, c.name, COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN admin_products p ON c.id = p.category_id
            WHERE c.parent_id = :parent_id
            GROUP BY c.id, c.name
            ORDER BY c.name
        ");
        $grandchildStmt->execute([':parent_id' => $groupId]);
        $grandchildren = $grandchildStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($grandchildren) > 0) {
            // This is a grouping header
            echo "📁 " . strtoupper($groupName) . " (Header - No Checkbox)\n";
            echo str_repeat("-", 70) . "\n";
            
            foreach ($grandchildren as $grandchild) {
                echo "   ☑️  " . $grandchild['name'] . " (" . $grandchild['product_count'] . " products)\n";
            }
            echo "\n";
        } else {
            // Direct category
            $productStmt = $pdo->prepare("
                SELECT COUNT(*) as count
                FROM admin_products
                WHERE category_id = :cat_id
            ");
            $productStmt->execute([':cat_id' => $groupId]);
            $productCount = $productStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            echo "☑️  " . $groupName . " (" . $productCount . " products)\n";
        }
    }
    
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "✅ Category hierarchy loaded successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
