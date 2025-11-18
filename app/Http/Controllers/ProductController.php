<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products list
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'reviews']);

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by price range
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->where('is_approved', true)
                  ->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }

        // Filter featured
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        // Get categories for filter
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    /**
     * Display product detail
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'images', 'reviews.user'])
            ->firstOrFail();

        // Increment view count
        $product->incrementViewCount();

        // Get related products (same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}