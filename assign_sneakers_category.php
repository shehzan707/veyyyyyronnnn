<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    // Create Sneakers category if it doesn't exist
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, slug, created_at, updated_at) VALUES (:name, :slug, NOW(), NOW())");
    $stmt->execute([
        ':name' => 'Sneakers',
        ':slug' => 'sneakers'
    ]);
    
    // Get the category ID
    $stmt = $pdo->query("SELECT id FROM categories WHERE name = 'Sneakers'");
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    $categoryId = $category['id'];
    
    echo "Sneakers category ID: $categoryId\n";
    
    // Update products 117 to 133
    $stmt = $pdo->prepare("UPDATE admin_products SET category_id = :category_id, category = :category_name WHERE id BETWEEN :id_start AND :id_end");
    $result = $stmt->execute([
        ':category_id' => $categoryId,
        ':category_name' => 'Sneakers',
        ':id_start' => 117,
        ':id_end' => 133
    ]);
    
    $affectedRows = $stmt->rowCount();
    echo "Updated $affectedRows products (ID 117-133) with Sneakers category.\n";
    
    // Verify the update
    $stmt = $pdo->query("SELECT id, name, category_id, category FROM admin_products WHERE id BETWEEN 117 AND 133");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nVerification:\n";
    foreach ($products as $product) {
        echo "Product ID: {$product['id']}, Name: {$product['name']}, Category ID: {$product['category_id']}, Category: {$product['category']}\n";
    }
    
    echo "\n✅ Successfully updated all products!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
