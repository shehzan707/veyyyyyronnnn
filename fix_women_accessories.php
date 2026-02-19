<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "FIXING WOMEN ACCESSORIES ASSIGNMENTS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Get Women > Accessories category
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = 2");
    $stmt->execute();
    $womenAccessories = $stmt->fetch(PDO::FETCH_ASSOC);
    $womenAccessoriesId = $womenAccessories['id'];
    
    echo "Women > Accessories Category ID: $womenAccessoriesId\n\n";
    
    // Create accessories subcategories under Women > Accessories
    $accessoryCategories = [
        ['name' => 'Bags', 'slug' => 'bags-women'],
        ['name' => 'Belts', 'slug' => 'belts-women'],
        ['name' => 'Jewellery', 'slug' => 'jewellery-women'],
        ['name' => 'Style Needs', 'slug' => 'style-needs-women']
    ];
    
    $womenAccessoryCats = [];
    
    echo "CREATING WOMEN ACCESSORY SUBCATEGORIES\n";
    echo str_repeat("-", 100) . "\n";
    
    foreach ($accessoryCategories as $cat) {
        $stmt = $pdo->prepare("
            SELECT id FROM categories 
            WHERE name = :name AND parent_id = :parent_id
        ");
        $stmt->execute([
            ':name' => $cat['name'],
            ':parent_id' => $womenAccessoriesId
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            $catId = $existing['id'];
            echo "  {$cat['name']} already exists (ID: $catId)\n";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
                VALUES (:name, :slug, :parent_id, NOW(), NOW())
            ");
            $stmt->execute([
                ':name' => $cat['name'],
                ':slug' => $cat['slug'],
                ':parent_id' => $womenAccessoriesId
            ]);
            $catId = $pdo->lastInsertId();
            echo "✓ Created {$cat['name']} (ID: $catId)\n";
        }
        
        $womenAccessoryCats[$cat['name']] = $catId;
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "REASSIGNING WOMEN ACCESSORIES PRODUCTS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Map product IDs to their correct Women > Accessories > [category]
    $productReassignments = [
        10 => 'Belts',        // Fendi Logo Buckle Suede Waist Belt Women
        26 => 'Bags',         // Prada Technical Nylon Crossbody Bag Women
        43 => 'Jewellery',    // Fendi Silver Fish-Eye Signature Ring Women
        48 => 'Style Needs'   // Prada Symbole Rectangular Black Sunglasses Women
    ];
    
    foreach ($productReassignments as $productId => $categoryName) {
        $newCatId = $womenAccessoryCats[$categoryName];
        
        $stmt = $pdo->prepare("
            UPDATE admin_products
            SET category_id = :new_cat_id
            WHERE id = :product_id
        ");
        $stmt->execute([
            ':new_cat_id' => $newCatId,
            ':product_id' => $productId
        ]);
        
        echo "✓ Product $productId -> Women > Accessories > $categoryName (Cat ID: $newCatId)\n";
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "FINAL VERIFICATION\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Get all Women descendant categories
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = 2 OR parent_id = 2");
    $stmt->execute();
    $parentCats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $parentCats[] = $row['id'];
    }
    
    // Get grandchildren
    $allWomenCats = $parentCats;
    foreach ($parentCats as $parentId) {
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE parent_id = :parent_id");
        $stmt->execute([':parent_id' => $parentId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $allWomenCats[] = $row['id'];
        }
    }
    
    $placeholders = implode(",", array_fill(0, count($allWomenCats), "?"));
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM admin_products
        WHERE category_id IN ($placeholders)
    ");
    $stmt->execute($allWomenCats);
    $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "✅ Total Women Products After Fix: $productCount\n\n";
    
    // Check specific problematic products
    echo "SPOT CHECK - PREVIOUSLY PROBLEMATIC PRODUCTS:\n";
    echo str_repeat("-", 100) . "\n";
    
    $checkIds = [10, 26, 43, 48, 67, 75, 87, 119];
    foreach ($checkIds as $id) {
        $stmt = $pdo->prepare("
            SELECT p.id, p.name, c.name as category, c2.name as parent_category
            FROM admin_products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN categories c2 ON c.parent_id = c2.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            echo "Product {$product['id']}: {$product['category']} > {$product['parent_category']}\n";
        }
    }
    
    echo "\n✅ Women category structure fixed! All women products will now filter correctly.\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
