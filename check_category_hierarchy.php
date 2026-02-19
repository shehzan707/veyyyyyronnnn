<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=veyronnnn;charset=utf8mb4', 'root', '');
    
    $stmt = $pdo->query("
        SELECT c.id, c.name, c.parent_id, p.name as parent_name 
        FROM categories c 
        LEFT JOIN categories p ON c.parent_id = p.id 
        ORDER BY c.parent_id, c.name
    ");
    
    echo "Category Hierarchy:\n";
    echo str_repeat("=", 80) . "\n";
    
    $currentParentId = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $indent = $row['parent_id'] ? "  ├─ " : "● ";
        $parentInfo = $row['parent_name'] ? " (Parent: {$row['parent_name']})" : "";
        echo "{$indent}{$row['name']}{$parentInfo}\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
