<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper methods
    public function totalItems()
    {
        return $this->items()->sum('quantity');
    }

    public function subtotal()
    {
        return $this->items()->get()->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function addItem($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        
        $cartItem = $this->items()->where('product_id', $productId)->first();
        
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $this->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }
    }

    public function updateItem($productId, $quantity)
    {
        $cartItem = $this->items()->where('product_id', $productId)->first();
        
        if ($cartItem) {
            if ($quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }
    }

    public function removeItem($productId)
    {
        $this->items()->where('product_id', $productId)->delete();
    }

    public function clear()
    {
        $this->items()->delete();
    }
}