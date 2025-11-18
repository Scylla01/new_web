<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        // Check if cart is empty
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        return view('frontend.checkout.index');
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string',
            'payment_method' => 'required|in:cod,bank_transfer,vnpay,momo',
            'note' => 'nullable|string',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        // Check cart
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            // Create or get shipping address
            if ($request->filled('address_id')) {
                $address = Address::findOrFail($request->address_id);
            } else {
                $address = Address::create([
                    'user_id' => auth()->id(),
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'province' => $validated['province'],
                    'district' => $validated['district'],
                    'ward' => $validated['ward'],
                    'address_detail' => $validated['address_detail'],
                    'is_default' => false,
                ]);
            }

            // Calculate totals
            $cart = session('cart');
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shippingFee = 30000;
            $discountAmount = session('discount', 0);
            $totalAmount = $subtotal + $shippingFee - $discountAmount;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $address->id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'note' => $validated['note'],
            ]);

            // Create order items
            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($id);
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            // Clear cart
            session()->forget('cart');
            session()->forget('discount');

            DB::commit();

            // Redirect to success page
            return redirect()->route('checkout.success', $order)->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display order success page
     */
    public function success(Order $order)
    {
        // Make sure user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('frontend.checkout.success', compact('order'));
    }
}