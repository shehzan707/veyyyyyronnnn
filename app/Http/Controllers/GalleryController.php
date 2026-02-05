<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display all product images from bulk folder
     */
    public function allImages()
    {
        $bulkPath = public_path('uploads/bulk');
        $images = [];

        if (File::isDirectory($bulkPath)) {
            $files = File::files($bulkPath);
            
            foreach ($files as $file) {
                $mimeType = File::mimeType($file->getPathname());
                
                // Only include image files
                if (strpos($mimeType, 'image') === 0) {
                    $images[] = [
                        'name' => $file->getFilename(),
                        'path' => 'uploads/bulk/' . $file->getFilename(),
                        'size' => $file->getSize(),
                        'url' => asset('uploads/bulk/' . $file->getFilename()),
                    ];
                }
            }
        }

        // Sort by filename
        usort($images, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        $totalImages = count($images);

        return view('gallery.all-images', compact('images', 'totalImages'));
    }

    /**
     * Get images by category
     */
    public function imagesByCategory($category)
    {
        $bulkPath = public_path('uploads/bulk');
        $images = [];

        if (File::isDirectory($bulkPath)) {
            $files = File::files($bulkPath);
            
            foreach ($files as $file) {
                $mimeType = File::mimeType($file->getPathname());
                
                if (strpos($mimeType, 'image') === 0) {
                    $filename = strtolower($file->getFilename());
                    $categoryLower = strtolower($category);
                    
                    // Match category in filename
                    if (strpos($filename, $categoryLower) !== false) {
                        $images[] = [
                            'name' => $file->getFilename(),
                            'path' => 'uploads/bulk/' . $file->getFilename(),
                            'size' => $file->getSize(),
                            'url' => asset('uploads/bulk/' . $file->getFilename()),
                        ];
                    }
                }
            }
        }

        usort($images, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        $totalImages = count($images);

        return view('gallery.category-images', compact('images', 'category', 'totalImages'));
    }

    /**
     * API endpoint to get all images as JSON
     */
    public function getImagesJson(Request $request)
    {
        $bulkPath = public_path('uploads/bulk');
        $images = [];
        $category = $request->query('category');

        if (File::isDirectory($bulkPath)) {
            $files = File::files($bulkPath);
            
            foreach ($files as $file) {
                $mimeType = File::mimeType($file->getPathname());
                
                if (strpos($mimeType, 'image') === 0) {
                    $filename = $file->getFilename();
                    
                    // Filter by category if provided
                    if ($category) {
                        $categoryLower = strtolower($category);
                        if (strpos(strtolower($filename), $categoryLower) === false) {
                            continue;
                        }
                    }
                    
                    $images[] = [
                        'name' => $filename,
                        'url' => asset('uploads/bulk/' . $filename),
                        'size' => $file->getSize(),
                    ];
                }
            }
        }

        usort($images, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return response()->json([
            'total' => count($images),
            'images' => $images,
            'category' => $category ?? 'all',
        ]);
    }
}
