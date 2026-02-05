<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Load parent categories with children (tree structure)
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // ❗ Prevent category becoming its own parent
        if ($request->parent_id == $category->id) {
            return back()->withErrors([
                'parent_id' => 'A category cannot be its own parent.',
            ]);
        }

        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete child categories first (important)
        $category->children()->delete();
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|json',
        ]);

        $categoryIds = json_decode($request->category_ids, true);

        if (empty($categoryIds)) {
            return redirect()
                ->route('admin.categories.index')
                ->withErrors('Please select at least one category to delete.');
        }

        $deletedCount = 0;

        foreach ($categoryIds as $id) {
            $category = Category::find($id);
            if ($category) {
                // Delete child categories first
                $category->children()->delete();
                $category->delete();
                $deletedCount++;
            }
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "✅ Deleted {$deletedCount} categor" . ($deletedCount === 1 ? 'y' : 'ies') . ' successfully!');
    }
}
