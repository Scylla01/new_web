<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'province',
        'district',
        'ward',
        'address_detail',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    // Helper methods
    public function fullAddress()
    {
        return $this->address_detail . ', ' . $this->ward . ', ' . $this->district . ', ' . $this->province;
    }

    // Auto update default address
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($address) {
            if ($address->is_default) {
                // Set all other addresses to non-default
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}