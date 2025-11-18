<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Auto generate slug
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Upload main image
            if ($request->hasFile('main_image')) {
                $validated['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            // Create product
            $product = Product::create($validated);

            // Upload additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Upload main image
            if ($request->hasFile('main_image')) {
                // Delete old image
                Storage::disk('public')->delete($product->main_image);
                $validated['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            // Update product
            $product->update($validated);

            // Upload additional images
            if ($request->hasFile('images')) {
                $currentMaxOrder = $product->images()->max('sort_order') ?? 0;
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $currentMaxOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Delete main image
            Storage::disk('public')->delete($product->main_image);

            // Delete additional images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Không thể xóa sản phẩm!');
        }
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Hình ảnh đã được xóa!');
    }

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        return back()->with('success', 'Trạng thái sản phẩm đã được cập nhật!');
    }

    public function toggleFeatured(Product $product)
    {
        $product->is_featured = !$product->is_featured;
        $product->save();

        return back()->with('success', 'Sản phẩm nổi bật đã được cập nhật!');
    }
}