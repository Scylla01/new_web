<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Helper methods
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        }

        // Percent discount
        $discount = ($subtotal * $this->value) / 100;
        
        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}