<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display homepage
     */
    public function index()
    {
        // Get active categories with product count
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        // Get featured products
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'reviews'])
            ->latest()
            ->take(8)
            ->get();

        // Get new products
        $newProducts = Product::where('is_active', true)
            ->with(['category', 'reviews'])
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'newProducts'));
    }
}