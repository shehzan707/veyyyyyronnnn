<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categoryModel', 'sizeVariants'])
            ->orderBy('id', 'desc')
            ->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'required|string',
            'stock' => 'required|integer|min:1',
        ]);

        $imagePath = null;
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $category = Category::find($request->category_id);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'category' => $category->name,
            'image' => $imagePath,
            'sizes' => json_encode($request->sizes),
            'stock' => $request->stock,
        ]);

        // Create size records with equal stock distribution
        foreach ($request->sizes as $size) {
            Size::create([
                'product_id' => $product->id,
                'size' => $size,
                'stock' => $request->stock,
                'is_available' => true,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully with size-wise inventory!');
    }

    public function edit($id)
    {
        $product = Product::with('sizeVariants')->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        $imagePath = $product->image;
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $category = Category::find($request->category_id);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'category' => $category->name,
            'image' => $imagePath,
            'sizes' => json_encode($request->sizes),
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        $size = $product->sizeVariants()->where('size', $request->size)->first();
        
        if ($size) {
            $size->update(['stock' => (int)$request->stock]);
            return response()->json(['success' => true, 'message' => "Stock for size {$request->size} updated successfully"]);
        }

        return response()->json(['success' => false, 'message' => 'Size not found'], 404);
    }

    public function toggleSizeAvailability(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'size' => 'required|string',
        ]);

        $size = $product->sizeVariants()->where('size', $request->size)->first();
        
        if ($size) {
            $size->update(['is_available' => !$size->is_available]);
            return response()->json(['success' => true, 'is_available' => $size->is_available, 'message' => "Size {$request->size} availability toggled"]);
        }

        return response()->json(['success' => false, 'message' => 'Size not found'], 404);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
