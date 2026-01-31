<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ZipArchive;
use App\Models\Product;
use App\Models\Size;
use App\Models\Category;

/**
 * BULK IMPORT SYSTEM - Standalone Controller
 * 
 * Copy this file to: app/Http/Controllers/BulkImportController.php
 * 
 * Features:
 * - CSV Product Import
 * - CSV Category Import
 * - ZIP Image Upload
 * - Auto-creates folders
 * - Dynamic categories
 * - No configuration needed
 */
class BulkImportController extends Controller
{
    /**
     * Show bulk operations page
     */
    public function index()
    {
        // Check if accessed from admin
        if (request()->routeIs('admin.bulk.import.index')) {
            return view('admin.products.bulk-import');
        }
        return view('bulk-import.index');
    }

    /**
     * Import products from CSV
     * CSV Format: name,description,price,category,sizes,stock,image_filename
     */
    public function importProducts(Request $request)
    {
        set_time_limit(300);
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        // Auto-create folders if not exist
        $this->ensureFoldersExist();

        $file = fopen($request->file('csv_file'), 'r');
        $header = fgetcsv($file);
        
        $imported = 0;
        $categoriesCreated = 0;
        $errors = [];
        
        try {
            while (($row = fgetcsv($file)) !== false) {
                if (empty(array_filter($row))) continue;
                
                try {
                    // Validate row has all required fields
                    if (count($row) < 6) {
                        $errors[] = "Row skipped: Not enough columns. Expected 6, got " . count($row);
                        continue;
                    }

                    // Trim all fields
                    $name = trim($row[0] ?? '');
                    $price = trim($row[1] ?? '');
                    $categoryName = trim($row[2] ?? '');
                    $sizes = trim($row[3] ?? '');
                    $stock = trim($row[4] ?? '');
                    $imageFile = trim($row[5] ?? '');

                    // Validate required fields
                    if (empty($name) || empty($price) || empty($sizes) || empty($stock)) {
                        $errors[] = "Row skipped: Missing required fields - Name: {$name}";
                        continue;
                    }

                    $categoryId = null;
                    
                    // Auto-create category if doesn't exist and get its ID
                    if (!empty($categoryName)) {
                        if ($this->createCategoryIfNotExists($categoryName)) {
                            $categoriesCreated++;
                        }
                        // Get the category ID
                        $category = Category::where('name', $categoryName)->first();
                        $categoryId = $category ? $category->id : null;
                    }
                    
                    // Handle image - make it optional
                    $imagePath = null;
                    if (!empty($imageFile)) {
                        $imagePath = $this->processImage($imageFile, $imported);
                    }

                    // Default VEYRON description
                    $defaultDescription = "VEYRON is not merely a brand—it is a declaration of authority in modern luxury, a name that embodies precision, restraint, and uncompromising excellence. Conceived at the intersection of contemporary fashion and timeless sophistication, Veyron represents a world where every detail is intentional and every design decision is driven by purpose. The brand speaks to individuals who value subtle dominance over loud expression—those who understand that true luxury does not seek attention, it commands it. Veyron's identity is built on refinement, confidence, and an unwavering commitment to elevating everyday essentials into statements of distinction.\n\nAt the core of VeyRON lies an obsession with craftsmanship and material integrity. Each product is the result of meticulous design engineering, where premium fabrics, advanced textiles, and superior finishes converge to create pieces that are both visually commanding and functionally superior. From the weight of a garment to the precision of its stitching, nothing is left to chance. Veyron's approach emphasizes durability without sacrificing elegance—ensuring that every item maintains its structure, texture, and presence over time. This is luxury designed not just to be worn, but to endure.\n\nVEYRON's design philosophy is defined by architectural minimalism and modern masculinity. Clean silhouettes, balanced proportions, and disciplined color palettes form the backbone of its aesthetic language. Rather than following transient trends, Veyron curates a timeless visual code—one that evolves intelligently while remaining rooted in its core values. Each collection is composed to deliver versatility, allowing pieces to transition seamlessly from formal environments to elevated casual settings. The result is a wardrobe that reflects confidence, control, and refined taste in every context.";
                    
                    // Create product using model
                    $product = Product::create([
                        'name' => $name,
                        'description' => $defaultDescription,
                        'price' => (float)$price,
                        'category_id' => $categoryId,
                        'category' => $categoryName,
                        'image' => $imagePath,
                        'sizes' => $sizes,
                        'stock' => (int)$stock,
                    ]);
                    
                    // Create size variants
                    $sizeArray = array_map('trim', explode(',', $sizes));
                    $stockValue = (int)$stock;
                    
                    foreach ($sizeArray as $size) {
                        if (!empty($size)) {
                            Size::create([
                                'product_id' => $product->id,
                                'size' => $size,
                                'stock' => $stockValue,
                                'is_available' => true,
                            ]);
                        }
                    }
                    
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Product error: " . $e->getMessage();
                }
            }
            
            fclose($file);
            
            $message = "✅ Imported {$imported} products!";
            if ($categoriesCreated > 0) {
                $message .= " Created {$categoriesCreated} new categories.";
            }
            if (!empty($errors)) {
                $message .= " ⚠️ Issues: " . implode(" | ", array_slice($errors, 0, 10));
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            fclose($file);
            return back()->withErrors('Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Import categories from CSV
     * CSV Format: name,description,is_active
     */
    public function importCategories(Request $request)
    {
        set_time_limit(300);
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        // Ensure categories table exists
        if (!$this->categoryTableExists()) {
            return back()->withErrors('Categories table not found. Run migrations first.');
        }

        $file = fopen($request->file('csv_file'), 'r');
        $header = fgetcsv($file);
        
        $imported = 0;
        
        try {
            while (($row = fgetcsv($file)) !== false) {
                if (empty(array_filter($row))) continue;
                
                try {
                    $categoryName = trim($row[0]);
                    
                    // Check if already exists
                    $exists = DB::table('categories')
                        ->where('name', $categoryName)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('categories')->insert([
                            'name' => $categoryName,
                            'slug' => Str::slug($categoryName),
                            'description' => $row[1] ?? '',
                            'is_active' => $row[2] ?? 1,
                            'sort_order' => DB::table('categories')->max('sort_order') + 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $imported++;
                    }
                } catch (\Exception $e) {
                    // Skip duplicates
                }
            }
            
            fclose($file);
            return back()->with('success', "✅ Imported {$imported} categories!");
            
        } catch (\Exception $e) {
            fclose($file);
            return back()->withErrors('Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Upload and extract ZIP of images
     */
    public function uploadImages(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip'
        ]);

        // Auto-create folders
        $this->ensureFoldersExist();

        $zip = new ZipArchive;
        $zipFile = $request->file('zip_file');
        $extractTo = public_path('uploads/products/');

        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractTo);
            $filesExtracted = $zip->numFiles;
            $zip->close();
            
            return back()->with('success', "✅ Extracted {$filesExtracted} images!");
        }
        
        return back()->withErrors('Failed to extract ZIP file.');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Auto-detect product table name
     */
    private function getProductTableName()
    {
        $tables = ['admin_products', 'products', 'items'];
        
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                return $table;
            }
        }
        
        return 'admin_products'; // Default fallback
    }

    /**
     * Check if categories table exists
     */
    private function categoryTableExists()
    {
        return DB::getSchemaBuilder()->hasTable('categories');
    }

    /**
     * Create category if it doesn't exist
     * Returns true if created, false if already exists
     */
    private function createCategoryIfNotExists($categoryName)
    {
        // Skip if categories table doesn't exist
        if (!$this->categoryTableExists()) {
            return false;
        }

        $exists = DB::table('categories')
            ->where('name', $categoryName)
            ->exists();
        
        if (!$exists) {
            DB::table('categories')->insert([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => 'Auto-created from import',
                'is_active' => true,
                'sort_order' => DB::table('categories')->max('sort_order') + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return true;
        }
        
        return false;
    }

    /**
     * Process and copy image from bulk folder to products folder
     */
    private function processImage($filename, $index)
    {
        $sourceImage = public_path('uploads/bulk/' . $filename);
        
        if (file_exists($sourceImage)) {
            $newImageName = time() . '_' . $index . '_' . $filename;
            $destImage = public_path('uploads/products/' . $newImageName);
            
            copy($sourceImage, $destImage);
            
            return 'uploads/products/' . $newImageName;
        }
        
        return null;
    }

    /**
     * Ensure required folders exist
     */
    private function ensureFoldersExist()
    {
        $folders = [
            public_path('uploads'),
            public_path('uploads/bulk'),
            public_path('uploads/products'),
        ];
        
        foreach ($folders as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }
        }
    }
}
