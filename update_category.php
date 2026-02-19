<?php
require_once __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Update the category
use App\Models\Category;
Category::where('name', 'T Shirts')->update(['name' => 'T Shirt']);
echo "Category updated successfully!\n";
?>
