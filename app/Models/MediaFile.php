<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $table = 'media_files';
    
    protected $fillable = [
        'file_name',
        'file_path',
        'media_type',
        'section',
        'banner_link',
        'is_enabled',
        'display_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get banners for a specific section
     */
    public static function getBySection($section = 'default')
    {
        return self::where('section', $section)
            ->where('is_enabled', true)
            ->orderBy('display_order', 'asc')
            ->get();
    }
}
