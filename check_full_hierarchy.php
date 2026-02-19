<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "FULL CATEGORY HIERARCHY - ALL CATEGORIES\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Get all root categories
    $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY name");
    $stmt->execute();
    
    function printCategoryTree($pdo, $parentId = null, $level = 0) {
        $indent = str_repeat("  ", $level);
        
        if ($parentId === null) {
            $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY name");
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = :parent_id ORDER BY name");
            $stmt->execute([':parent_id' => $parentId]);
        }
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$indent}├─ ID: {$row['id']}, Name: {$row['name']}\n";
            printCategoryTree($pdo, $row['id'], $level + 1);
        }
    }
    
    printCategoryTree($pdo);
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "CHECKING SPECIFIC PROBLEMATIC PRODUCTS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Check where category 31, 32, 33, 72 fit in the hierarchy
    $categories = [31, 32, 33, 39, 42, 46, 48, 72];
    
    foreach ($categories as $catId) {
        $stmt = $pdo->prepare("
            SELECT c.id, c.name, c.parent_id, p.name as parent_name
            FROM categories c
            LEFT JOIN categories p ON c.parent_id = p.id
            WHERE c.id = :id
        ");
        $stmt->execute([':id' => $catId]);
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cat) {
            echo "Category ID: {$cat['id']}\n";
            echo "  Name: {$cat['name']}\n";
            echo "  Parent ID: {$cat['parent_id']}\n";
            echo "  Parent Name: {$cat['parent_name']}\n";
            
            // Check if this category is under Women
            $isUnderWomen = false;
            $checkParentId = $cat['parent_id'];
            while ($checkParentId) {
                $stmt2 = $pdo->prepare("SELECT parent_id, name FROM categories WHERE id = :id");
                $stmt2->execute([':id' => $checkParentId]);
                $parentCat = $stmt2->fetch(PDO::FETCH_ASSOC);
                if ($parentCat['name'] === 'Women') {
                    $isUnderWomen = true;
                    break;
                }
                $checkParentId = $parentCat['parent_id'];
            }
            
            echo "  Under Women: " . ($isUnderWomen ? "✓ YES" : "✗ NO") . "\n\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
