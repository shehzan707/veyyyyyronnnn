<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "FIXING CATEGORY STRUCTURE\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Step 1: Check if Women > Footwear exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Footwear' AND parent_id = 2");
    $stmt->execute();
    $womenFootwear = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$womenFootwear) {
        echo "Creating Women > Footwear category...\n";
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
            VALUES ('Footwear', 'women-footwear', 2, NOW(), NOW())
        ");
        $stmt->execute();
        $womenFootwearId = $pdo->lastInsertId();
        echo "✓ Created Women > Footwear (ID: $womenFootwearId)\n";
    } else {
        $womenFootwearId = $womenFootwear['id'];
        echo "✓ Women > Footwear already exists (ID: $womenFootwearId)\n";
    }
    
    echo "\n";
    
    // Step 2: Check if Women > Accessories exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = 2");
    $stmt->execute();
    $womenAccessories = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$womenAccessories) {
        echo "Creating Women > Accessories category...\n";
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
            VALUES ('Accessories', 'women-accessories', 2, NOW(), NOW())
        ");
        $stmt->execute();
        $womenAccessoriesId = $pdo->lastInsertId();
        echo "✓ Created Women > Accessories (ID: $womenAccessoriesId)\n";
    } else {
        $womenAccessoriesId = $womenAccessories['id'];
        echo "✓ Women > Accessories already exists (ID: $womenAccessoriesId)\n";
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "MOVING FOOTWEAR CATEGORIES\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Step 3: Move Footwear > Women children to Women > Footwear
    // Get current Footwear > Women (ID: 30)
    $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = 30");
    $stmt->execute();
    
    $footwearCategoriesMap = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Create new category under Women > Footwear with parent as Women Footwear
        $newCatStmt = $pdo->prepare("
            INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
            SELECT name, slug, :new_parent_id, created_at, updated_at
            FROM categories
            WHERE id = :old_id
        ");
        $newCatStmt->execute([
            ':new_parent_id' => $womenFootwearId,
            ':old_id' => $row['id']
        ]);
        
        $newCatId = $pdo->lastInsertId();
        $footwearCategoriesMap[$row['id']] = $newCatId;
        
        echo "✓ Created Women > Footwear > {$row['name']} (ID: $newCatId)\n";
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "REASSIGNING PRODUCTS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Step 4: Move products from old footwear women categories to new ones
    foreach ($footwearCategoriesMap as $oldCatId => $newCatId) {
        $updateStmt = $pdo->prepare("
            UPDATE admin_products
            SET category_id = :new_cat_id
            WHERE category_id = :old_cat_id
        ");
        $updateStmt->execute([
            ':new_cat_id' => $newCatId,
            ':old_cat_id' => $oldCatId
        ]);
        
        $affectedRows = $updateStmt->rowCount();
        if ($affectedRows > 0) {
            echo "✓ Moved $affectedRows products from category $oldCatId to $newCatId\n";
        }
    }
    
    echo "\n" . str_repeat("=", 100) . "\n";
    echo "REASSIGNING WOMEN ACCESSORIES TO WOMEN > ACCESSORIES\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Step 5: Create Women > Accessories > [specific categories]
    // Products with Women names in Accessories
    $womensAccessoryCats = [
        42 => ['name' => 'Belts', 'female-parent' => 'Leather Goods'],
        44 => ['name' => 'Bags', 'female-parent' => 'Bags'],
        48 => ['name' => 'Rings', 'female-parent' => 'Jewellery'],
        39 => ['name' => 'Sunglasses', 'female-parent' => 'Style Needs']
    ];
    
    $accessoriesProductMap = [];
    
    foreach ($womensAccessoryCats as $oldCatId => $catInfo) {
        // Check if Women > Accessories > [category] exists
        $checkStmt = $pdo->prepare("
            SELECT id FROM categories 
            WHERE name = :name AND parent_id = :parent_id
        ");
        $checkStmt->execute([
            ':name' => $catInfo['name'],
            ':parent_id' => $womenAccessoriesId
        ]);
        $existingCat = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingCat) {
            $newCatId = $existingCat['id'];
        } else {
            // Create it
            $createStmt = $pdo->prepare("
                INSERT INTO categories (name, slug, parent_id, created_at, updated_at)
                VALUES (:name, :slug, :parent_id, NOW(), NOW())
            ");
            $createStmt->execute([
                ':name' => $catInfo['name'],
                ':slug' => str_replace(' ', '-', strtolower($catInfo['name'])),
                ':parent_id' => $womenAccessoriesId
            ]);
            $newCatId = $pdo->lastInsertId();
        }
        
        $accessoriesProductMap[$oldCatId] = $newCatId;
        echo "✓ Created/Found Women > Accessories > {$catInfo['name']} (ID: $newCatId)\n";
    }
    
    // Now move the women products from old categories to new ones
    echo "\nReassigning women accessories products:\n";
    
    // Find and reassign the specific woman products
    $womenProductIds = [10, 26, 43, 48]; // Belt, Bag, Ring, Sunglasses women products
    
    $reassignMap = [
        10 => 42, // Fendi Logo Buckle Suede Waist Belt Women -> Belts
        26 => 44, // Prada Technical Nylon Crossbody Bag Women -> Bags
        43 => 48, // Fendi Silver Fish-Eye Signature Ring Women -> Rings
        48 => 39  // Prada Symbole Rectangular Black Sunglasses Women -> Sunglasses
    ];
    
    foreach ($reassignMap as $productId => $oldCatId) {
        if (isset($accessoriesProductMap[$oldCatId])) {
            $newCatId = $accessoriesProductMap[$oldCatId];
            $updateStmt = $pdo->prepare("
                UPDATE admin_products
                SET category_id = :new_cat_id
                WHERE id = :product_id
            ");
            $updateStmt->execute([
                ':new_cat_id' => $newCatId,
                ':product_id' => $productId
            ]);
            echo "✓ Moved product $productId to category $newCatId\n";
        }
    }
    
    echo "\n✅ Category structure fixed!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
