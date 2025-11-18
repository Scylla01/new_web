<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart
     */
    public function index()
    {
        return view('frontend.cart.index');
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ!');
        }

        // Get cart from session
        $cart = session()->get('cart', []);

        // If product already exists in cart, update quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            // Add new product to cart
            $cart[$product->id] = [
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->main_image,
                'sku' => $product->sku,
            ];
        }

        // Save cart to session
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            // Check stock
            $product = Product::find($request->id);
            if ($product && $product->stock_quantity < $request->quantity) {
                return back()->with('error', 'Số lượng sản phẩm không đủ!');
            }

            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            return back()->with('success', 'Đã cập nhật giỏ hàng!');
        }

        return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng!');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            
            return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }

        return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        
        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        // TODO: Implement coupon logic
        
        return back()->with('error', 'Mã giảm giá không hợp lệ!');
    }
}