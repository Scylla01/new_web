<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of banners
     */
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link' => 'nullable|url',
        'button_text' => 'nullable|string|max:50',
        'sort_order' => 'nullable|integer|min:0',
        // BỎ validation is_active ở đây
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('banners', 'public');
        $validated['image'] = $imagePath;
    }

    // Xử lý is_active SAU validation
    $validated['is_active'] = $request->has('is_active') ? true : false;
    $validated['sort_order'] = $validated['sort_order'] ?? 0;

    Banner::create($validated);

    return redirect()->route('admin.banners.index')
        ->with('success', 'Banner đã được tạo thành công!');
}

    /**
     * Show the form for editing the banner
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the banner
     */
    public function update(Request $request, Banner $banner)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link' => 'nullable|url',
        'button_text' => 'nullable|string|max:50',
        'sort_order' => 'nullable|integer|min:0',
        // BỎ validation is_active
    ]);

    // Handle new image upload
    if ($request->hasFile('image')) {
        // Delete old image
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $imagePath = $request->file('image')->store('banners', 'public');
        $validated['image'] = $imagePath;
    }

    // Xử lý is_active SAU validation
    $validated['is_active'] = $request->has('is_active') ? true : false;
    $validated['sort_order'] = $validated['sort_order'] ?? 0;

    $banner->update($validated);

    return redirect()->route('admin.banners.index')
        ->with('success', 'Banner đã được cập nhật!');
}

    /**
     * Remove the banner
     */
    public function destroy(Banner $banner)
    {
        // Delete image file
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được xóa!');
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        return back()->with('success', 'Trạng thái banner đã được cập nhật!');
    }
}