<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $sql = "TRUNCATE TABLE categories";
    $pdo->exec($sql);
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    
    $sql = "INSERT INTO categories (name, slug, sort_order, is_active, created_at, updated_at) VALUES 
            ('MEN', 'men', 1, 1, NOW(), NOW()),
            ('WOMEN', 'women', 2, 1, NOW(), NOW()),
            ('ACCESSORIES', 'accessories', 3, 1, NOW(), NOW()),
            ('FOOTWEAR', 'footwear', 4, 1, NOW(), NOW())";
    
    $pdo->exec($sql);
    
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY sort_order");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Categories Restored!\n";
    foreach ($categories as $cat) {
        echo "  • {$cat['name']}\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
