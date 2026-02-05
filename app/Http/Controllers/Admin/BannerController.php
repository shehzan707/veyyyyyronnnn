<?php

namespace App\Http\Controllers\Admin;

use App\Models\MediaFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index()
    {
        $banners = MediaFile::orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,webm,ogg,mov,avi|max:204800', // Increased to 200MB for high-quality videos
            'section' => 'required|in:default,men,women,accessories,footwear',
            'banner_link' => 'nullable|url',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        
        // Determine if it's an image or video
        if (strpos($mimeType, 'image') !== false) {
            $mediaType = 'image';
            $folder = 'banners/images';
            $path = $file->storeAs($folder, $file->getClientOriginalName(), 'public');
        } else {
            // For videos: preserve original format and quality
            $mediaType = 'video';
            $folder = 'banners/videos';
            
            // Use original name to preserve quality and codec
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs($folder, $originalName, 'public');
            
            // Optional: Log video info for debugging
            \Log::info('Video uploaded', [
                'name' => $originalName,
                'size' => $file->getSize(),
                'mime' => $mimeType,
                'path' => $path,
            ]);
        }

        // Create banner with all new fields
        MediaFile::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => 'storage/' . $path,
            'media_type' => $mediaType,
            'section' => $request->input('section', 'default'),
            'banner_link' => $request->input('banner_link'),
            'is_enabled' => true,
            'display_order' => $request->input('display_order', 0),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner uploaded successfully!');
    }

    /**
     * Edit banner (show edit form)
     */
    public function edit($id)
    {
        $banner = MediaFile::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update banner details
     */
    public function update(Request $request, $id)
    {
        $banner = MediaFile::findOrFail($id);

        $request->validate([
            'section' => 'required|in:default,men,women,accessories,footwear',
            'banner_link' => 'nullable|url',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $banner->update([
            'section' => $request->input('section'),
            'banner_link' => $request->input('banner_link'),
            'display_order' => $request->input('display_order', 0),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully!');
    }

    /**
     * Toggle banner enabled/disabled status
     */
    public function toggle($id)
    {
        $banner = MediaFile::findOrFail($id);
        $banner->update([
            'is_enabled' => !$banner->is_enabled
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner status updated!');
    }

    public function destroy($id)
    {
        $banner = MediaFile::findOrFail($id);
        
        // Delete the file from storage
        if (file_exists(public_path($banner->file_path))) {
            unlink(public_path($banner->file_path));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully!');
    }
}