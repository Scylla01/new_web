<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'button_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active banners ordered by sort_order
     */
    public static function getActiveBanners()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}