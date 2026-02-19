<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "FIXING WOMEN CATEGORY ASSIGNMENTS - DIRECT APPROACH\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Step 1: Create Women > Footwear if it doesn't exist
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Footwear' AND parent_id = 2");
    $stmt->execute();
    $womenFootwear = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$womenFootwear) {
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
            VALUES ('Footwear', 'footwear-women', 2, NOW(), NOW())
        ");
        $stmt->execute();
        $womenFootwearId = $pdo->lastInsertId();
        echo "✓ Created Women > Footwear (ID: $womenFootwearId)\n";
    } else {
        $womenFootwearId = $womenFootwear['id'];
        echo "✓ Women > Footwear exists (ID: $womenFootwearId)\n";
    }
    
    // Step 2: Create Women > Accessories if it doesn't exist
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = 2");
    $stmt->execute();
    $womenAccessories = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$womenAccessories) {
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
            VALUES ('Accessories', 'accessories-women', 2, NOW(), NOW())
        ");
        $stmt->execute();
        $womenAccessoriesId = $pdo->lastInsertId();
        echo "✓ Created Women > Accessories (ID: $womenAccessoriesId)\n";
    } else {
        $womenAccessoriesId = $womenAccessories['id'];
        echo "✓ Women > Accessories exists (ID: $womenAccessoriesId)\n";
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "CREATING WOMEN-SPECIFIC FOOTWEAR SUBCATEGORIES\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Create Women-specific footwear categories under Women > Footwear
    $footwearCategories = [
        ['name' => 'Casual Shoes', 'slug' => 'casual-shoes-women'],
        ['name' => 'Heels', 'slug' => 'heels-women'],
        ['name' => 'Sandals', 'slug' => 'sandals-women'],
        ['name' => 'Sneakers', 'slug' => 'sneakers-women']
    ];
    
    $womenFootwearCats = [];
    
    foreach ($footwearCategories as $cat) {
        $stmt = $pdo->prepare("
            SELECT id FROM categories 
            WHERE name = :name AND parent_id = :parent_id
        ");
        $stmt->execute([
            ':name' => $cat['name'],
            ':parent_id' => $womenFootwearId
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
                ':parent_id' => $womenFootwearId
            ]);
            $catId = $pdo->lastInsertId();
            echo "✓ Created {$cat['name']} (ID: $catId)\n";
        }
        
        $womenFootwearCats[$cat['name']] = $catId;
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "REASSIGNING WOMEN FOOTWEAR PRODUCTS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Products to reassign from Footwear > Women > [category] to Women > Footwear > [category]
    $productReassignments = [
        // (product_id => new_category_name)
        67 => 'Casual Shoes',  // Brooks Running Premium Ghost Women -> Women > Footwear > Casual Shoes
        75 => 'Heels',         // Luxury Premium Heels Women -> Women > Footwear > Heels
        76 => 'Heels',         // Classic Elegant Heels Women -> Women > Footwear > Heels
        77 => 'Heels',         // Luxury Comfort Heels Women -> Women > Footwear > Heels
        78 => 'Heels',         // Premium Party Heels Women -> Women > Footwear > Heels
        79 => 'Heels',         // Classic Style Evening Heels Women -> Women > Footwear > Heels
        87 => 'Casual Shoes',  // Nike Cortez Premium Shoes Women -> Women > Footwear > Casual Shoes
        112 => 'Casual Shoes', // Nike Court Premium Vision Women -> Women > Footwear > Casual Shoes
        113 => 'Casual Shoes', // Nike Air Force Premium Women -> Women > Footwear > Casual Shoes
        119 => 'Sneakers',     // Nike Cortez Premium Shoes Women -> Women > Footwear > Sneakers
        120 => 'Sneakers',     // Nike Cortez Luxury Shoes Women -> Women > Footwear > Sneakers
        121 => 'Sneakers',     // Nike Cortez Classic Collection Women -> Women > Footwear > Sneakers
        122 => 'Sneakers',     // Nike Cortez Vintage Casual Women -> Women > Footwear > Sneakers
        123 => 'Sneakers'      // Nike Court Vision Premium Women -> Women > Footwear > Sneakers
    ];
    
    foreach ($productReassignments as $productId => $categoryName) {
        $newCatId = $womenFootwearCats[$categoryName];
        
        $stmt = $pdo->prepare("
            UPDATE admin_products
            SET category_id = :new_cat_id
            WHERE id = :product_id
        ");
        $stmt->execute([
            ':new_cat_id' => $newCatId,
            ':product_id' => $productId
        ]);
        
        echo "✓ Product $productId -> Women > Footwear > $categoryName (Cat ID: $newCatId)\n";
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "VERIFICATION\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Verify women products are now under Women
    $stmt = $pdo->prepare("
        SELECT GROUP_CONCAT(DISTINCT c.id) as cat_ids
        FROM categories c
        WHERE c.id = 2 OR c.parent_id = 2 OR c.parent_id IN (
            SELECT id FROM categories WHERE parent_id = 2
        )
    ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $allWomenCatIds = array_map('trim', explode(',', $result['cat_ids']));
    
    $placeholders = implode(",", array_fill(0, count($allWomenCatIds), "?"));
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM admin_products
        WHERE category_id IN ($placeholders)
    ");
    $stmt->execute($allWomenCatIds);
    $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "✅ Total Women Products: $productCount\n";
    echo "✅ All Women products should now filter correctly!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
