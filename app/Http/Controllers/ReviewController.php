<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'comment.required' => 'Vui lòng nhập nhận xét.',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $validated['product_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false, // Wait for admin approval
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn sẽ được hiển thị sau khi được duyệt.');
    }
}