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
                    $categoryName = trim($row[3] ?? '');
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
                    
                    // Handle image
                    $imagePath = null;
                    if (!empty($row[6])) {
                        $imagePath = $this->processImage($row[6], $imported);
                        if (!$imagePath) {
                            $errors[] = "Image not found: {$row[6]}";
                        }
                    }
                    
                    // Create product using model
                    $product = Product::create([
                        'name' => $row[0],
                        'description' => $row[1] ?? '',
                        'price' => (float)$row[2],
                        'category_id' => $categoryId,
                        'category' => $categoryName,
                        'image' => $imagePath,
                        'sizes' => $row[4] ?? '',
                        'stock' => (int)($row[5] ?? 0),
                    ]);
                    
                    // Create size variants
                    $sizes = array_map('trim', explode(',', $row[4] ?? ''));
                    $stock = (int)($row[5] ?? 0);
                    
                    foreach ($sizes as $size) {
                        if (!empty($size)) {
                            Size::create([
                                'product_id' => $product->id,
                                'size' => $size,
                                'stock' => $stock,
                                'is_available' => true,
                            ]);
                        }
                    }
                    
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Row error: " . $e->getMessage();
                }
            }
            
            fclose($file);
            
            $message = "✅ Imported {$imported} products!";
            if ($categoriesCreated > 0) {
                $message .= " Created {$categoriesCreated} new categories.";
            }
            if (!empty($errors) && count($errors) < 5) {
                $message .= " Errors: " . implode(', ', $errors);
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
