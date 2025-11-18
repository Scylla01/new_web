<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'compare_price',
        'stock_quantity',
        'sku',
        'main_image',
        'is_active',
        'is_featured',
        'view_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper methods
    public function inStock()
    {
        return $this->stock_quantity > 0;
    }

    public function hasDiscount()
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function discountPercent()
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function averageRating()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}