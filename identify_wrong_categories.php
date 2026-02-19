<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    echo "ANALYZING MISASSIGNED WOMEN PRODUCTS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Get problematic products
    $problemProducts = [
        10 => ['name' => 'Fendi Logo Buckle Suede Waist Belt Women', 'should_be' => 'Belts (Accessories)'],
        26 => ['name' => 'Prada Technical Nylon Crossbody Bag Women', 'should_be' => 'Bags (Accessories)'],
        43 => ['name' => 'Fendi Silver Fish-Eye Signature Ring Women', 'should_be' => 'Rings (Accessories)'],
        48 => ['name' => 'Prada Symbole Rectangular Black Sunglasses Women', 'should_be' => 'Sunglasses (Accessories)'],
        67 => ['name' => 'Brooks Running Premium Ghost Women', 'should_be' => 'Casual Shoes (Women Footwear)'],
        75 => ['name' => 'Luxury Premium Heels Women', 'should_be' => 'Heels (Women Footwear)'],
        76 => ['name' => 'Classic Elegant Heels Women', 'should_be' => 'Heels (Women Footwear)'],
        77 => ['name' => 'Luxury Comfort Heels Women', 'should_be' => 'Heels (Women Footwear)'],
        78 => ['name' => 'Premium Party Heels Women', 'should_be' => 'Heels (Women Footwear)'],
        79 => ['name' => 'Classic Style Evening Heels Women', 'should_be' => 'Heels (Women Footwear)'],
        87 => ['name' => 'Nike Cortez Premium Shoes Women', 'should_be' => 'Casual Shoes (Women Footwear)'],
        112 => ['name' => 'Nike Court Premium Vision Women', 'should_be' => 'Casual Shoes (Women Footwear)'],
        113 => ['name' => 'Nike Air Force Premium Women', 'should_be' => 'Casual Shoes (Women Footwear)'],
        119 => ['name' => 'Nike Cortez Premium Shoes Women', 'should_be' => 'Sneakers (Women Footwear)'],
        120 => ['name' => 'Nike Cortez Luxury Shoes Women', 'should_be' => 'Sneakers (Women Footwear)'],
        121 => ['name' => 'Nike Cortez Classic Collection Women', 'should_be' => 'Sneakers (Women Footwear)'],
        122 => ['name' => 'Nike Cortez Vintage Casual Women', 'should_be' => 'Sneakers (Women Footwear)'],
        123 => ['name' => 'Nike Court Vision Premium Women', 'should_be' => 'Sneakers (Women Footwear)'],
    ];
    
    echo "WRONG CATEGORY ASSIGNMENTS:\n";
    echo str_repeat("-", 100) . "\n";
    
    foreach ($problemProducts as $productId => $info) {
        $stmt = $pdo->prepare("
            SELECT p.id, p.name, p.category_id, c.name as current_category, c.parent_id
            FROM admin_products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            echo "ID: {$product['id']}\n";
            echo "  Name: {$product['name']}\n";
            echo "  Current Category ID: {$product['category_id']} ({$product['current_category']})\n";
            echo "  Should Be: {$info['should_be']}\n\n";
        }
    }
    
    echo str_repeat("=", 100) . "\n";
    echo "FINDING CORRECT CATEGORY IDS\n";
    echo str_repeat("=", 100) . "\n\n";
    
    // Find Women Footwear categories
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, p.name as parent_name
        FROM categories c
        LEFT JOIN categories p ON c.parent_id = p.id
        WHERE c.name IN ('Heels', 'Sandals', 'Casuals Shoes', 'Casual Shoes')
        OR (c.parent_id IN (SELECT id FROM categories WHERE name IN ('Casual Shoes', 'Heels', 'Sandals')))
        ORDER BY c.parent_id, c.name
    ");
    $stmt->execute();
    
    echo "Women Footwear Categories:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ID: {$row['id']}, Name: {$row['name']}, Parent: {$row['parent_name']}\n";
    }
    
    echo "\n";
    
    // Find Women Accessories (shouldn't be there, but checking)
    $stmt = $pdo->prepare("
        SELECT DISTINCT c.id, c.name
        FROM categories c
        WHERE c.name IN ('Belts', 'Bags', 'Rings', 'Sunglasses')
        ORDER BY c.name
    ");
    $stmt->execute();
    
    echo "Accessories Categories (for comparison):\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ID: {$row['id']}, Name: {$row['name']}\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
