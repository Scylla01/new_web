@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')

<!-- ✅ BANNER WITH SIDEBAR CATEGORIES -->
<section class="banner-section py-4">
    <div class="container">
        <div class="row g-3">
            <!-- Left Sidebar - Categories -->
<div class="col-lg-3">
    <div class="category-sidebar">
        <div class="category-sidebar-header">
            <i class="fas fa-bars"></i>
            <span>DANH MỤC SẢN PHẨM</span>
        </div>
        <ul class="category-sidebar-list">
            @foreach(\App\Models\Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->limit(12)->get() as $category)
            <li>
                <a href="{{ route('products.index', ['category' => $category->id]) }}">
                    {{-- Dynamic Icon based on category name --}}
                    <i class="fas 
                        @if(stripos($category->name, 'bánh') !== false && stripos($category->name, 'kẹo') !== false)
                            fa-cookie-bite
                        @elseif(stripos($category->name, 'bánh') !== false)
                            fa-birthday-cake
                        @elseif(stripos($category->name, 'kẹo') !== false)
                            fa-candy-cane
                        @elseif(stripos($category->name, 'đồ uống') !== false)
                            fa-coffee
                        @elseif(stripos($category->name, 'nước') !== false && stripos($category->name, 'ngọt') !== false)
                            fa-glass-whiskey
                        @elseif(stripos($category->name, 'sữa') !== false)
                            fa-baby-carriage
                        @elseif(stripos($category->name, 'trái cây') !== false || stripos($category->name, 'hoa quả') !== false)
                            fa-apple-alt
                        @elseif(stripos($category->name, 'rau') !== false || stripos($category->name, 'củ') !== false)
                            fa-carrot
                        @elseif(stripos($category->name, 'thịt') !== false)
                            fa-drumstick-bite
                        @elseif(stripos($category->name, 'cá') !== false || stripos($category->name, 'hải sản') !== false)
                            fa-fish
                        @elseif(stripos($category->name, 'thực phẩm') !== false)
                            fa-hamburger
                        @elseif(stripos($category->name, 'gia dụng') !== false || stripos($category->name, 'nhà bếp') !== false)
                            fa-home
                        @elseif(stripos($category->name, 'điện tử') !== false)
                            fa-tv
                        @elseif(stripos($category->name, 'mỹ phẩm') !== false || stripos($category->name, 'làm đẹp') !== false)
                            fa-palette
                        @elseif(stripos($category->name, 'chăm sóc da') !== false || stripos($category->name, 'skincare') !== false)
                            fa-hand-sparkles
                        @elseif(stripos($category->name, 'nước hoa') !== false)
                            fa-spray-can
                        @elseif(stripos($category->name, 'thời trang') !== false || stripos($category->name, 'quần áo') !== false)
                            fa-tshirt
                        @elseif(stripos($category->name, 'giày') !== false || stripos($category->name, 'dép') !== false)
                            fa-shoe-prints
                        @elseif(stripos($category->name, 'phụ kiện') !== false || stripos($category->name, 'túi') !== false)
                            fa-shopping-bag
                        @elseif(stripos($category->name, 'kính') !== false)
                            fa-glasses
                        @elseif(stripos($category->name, 'sức khỏe') !== false || stripos($category->name, 'y tế') !== false)
                            fa-heartbeat
                        @elseif(stripos($category->name, 'thuốc') !== false)
                            fa-pills
                        @elseif(stripos($category->name, 'vitamin') !== false || stripos($category->name, 'thực phẩm chức năng') !== false)
                            fa-tablets
                        @elseif(stripos($category->name, 'văn phòng') !== false || stripos($category->name, 'viết') !== false)
                            fa-pen
                        @elseif(stripos($category->name, 'sách') !== false)
                            fa-book
                        @elseif(stripos($category->name, 'đồ chơi') !== false)
                            fa-gamepad
                        @elseif(stripos($category->name, 'trẻ em') !== false || stripos($category->name, 'bé') !== false)
                            fa-child
                        @elseif(stripos($category->name, 'thể thao') !== false || stripos($category->name, 'gym') !== false)
                            fa-dumbbell
                        @elseif(stripos($category->name, 'điện thoại') !== false || stripos($category->name, 'phone') !== false)
                            fa-mobile-alt
                        @elseif(stripos($category->name, 'máy tính') !== false || stripos($category->name, 'laptop') !== false)
                            fa-laptop
                        @elseif(stripos($category->name, 'đồng hồ') !== false || stripos($category->name, 'watch') !== false)
                            fa-clock
                        @elseif(stripos($category->name, 'nội thất') !== false || stripos($category->name, 'furniture') !== false)
                            fa-couch
                        @elseif(stripos($category->name, 'đèn') !== false || stripos($category->name, 'light') !== false)
                            fa-lightbulb
                        @elseif(stripos($category->name, 'xe') !== false || stripos($category->name, 'ô tô') !== false)
                            fa-car
                        @elseif(stripos($category->name, 'xe máy') !== false || stripos($category->name, 'motor') !== false)
                            fa-motorcycle
                        @elseif(stripos($category->name, 'pet') !== false || stripos($category->name, 'thú cưng') !== false)
                            fa-paw
                        @elseif(stripos($category->name, 'hoa') !== false)
                            fa-leaf
                        @elseif(stripos($category->name, 'cây') !== false)
                            fa-seedling
                        @else
                            fa-tags
                        @endif
                        " style="color: var(--primary-green);"></i>
                    
                    <span>{{ $category->name }}</span>
                    
                    @if($category->products_count > 0)
                    <small>({{ $category->products_count }})</small>
                    @endif
                    
                    @if($category->children->count() > 0)
                    <i class="fas fa-chevron-right ms-auto"></i>
                    @endif
                </a>
                
                <!-- Submenu nếu có -->
                @if($category->children->count() > 0)
                <div class="category-submenu">
                    <div class="submenu-header">{{ $category->name }}</div>
                    <div class="row">
                        @foreach($category->children as $child)
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('products.index', ['category' => $child->id]) }}" class="submenu-item">
                                <i class="fas fa-angle-right"></i> {{ $child->name }}
                                @if($child->products_count > 0)
                                <small>({{ $child->products_count }})</small>
                                @endif
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>

            <!-- Right - Banner Carousel -->
            <div class="col-lg-9">
                @if(isset($banners) && $banners->count() > 0)
                <div id="bannerCarousel" class="carousel slide banner-carousel" data-bs-ride="carousel">
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        @foreach($banners as $index => $banner)
                        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}" 
                                class="{{ $index == 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner">
                        @foreach($banners as $index => $banner)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            @if($banner->link)
                            <a href="{{ $banner->link }}">
                            @endif
                                <img src="{{ $banner->image_url }}" class="d-block w-100" alt="{{ $banner->title }}">
                                @if($banner->title || $banner->description || $banner->button_text)
                                <div class="carousel-caption">
                                    <div class="banner-content">
                                        @if($banner->title)
                                        <h2 class="banner-title">{{ $banner->title }}</h2>
                                        @endif
                                        @if($banner->description)
                                        <p class="banner-description">{{ $banner->description }}</p>
                                        @endif
                                        @if($banner->button_text && $banner->link)
                                        <a href="{{ $banner->link }}" class="btn btn-light btn-banner">
                                            {{ $banner->button_text }} <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            @if($banner->link)
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Controls -->
                    @if($banners->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
                @else
                <!-- Fallback Banner -->
                <div class="fallback-banner">
                    <div class="hero-section">
                        <div class="text-center py-5">
                            <h1 class="display-4 fw-bold mb-4">Chào mừng đến với Bách Hóa Shop</h1>
                            <p class="lead mb-4">Mua sắm dễ dàng, tiện lợi với hàng ngàn sản phẩm chất lượng</p>
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- KẾT THÚC BANNER SECTION -->

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-star text-warning"></i> Sản Phẩm Nổi Bật</h2>
            <a href="{{ route('products.index', ['featured' => 1]) }}" class="btn btn-outline-primary">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
            <div class="col-md-3">
                <div class="card product-card">
                    <div class="position-relative">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image">
                        </a>
                        @if($product->hasDiscount())
                            <span class="product-badge">-{{ $product->discountPercent() }}%</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                        </h6>
                        <div class="mb-2">
                            <span class="product-price">{{ number_format($product->price) }}đ</span>
                            @if($product->compare_price)
                                <span class="product-price-old ms-2">{{ number_format($product->compare_price) }}đ</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-star text-warning"></i>
                                <span>{{ number_format($product->averageRating(), 1) }}</span>
                                <small class="text-muted">({{ $product->reviews->count() }})</small>
                            </div>
                            @if($product->inStock())
                                <button class="btn btn-sm btn-primary" onclick="addToCart({{ $product->id }})">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Chưa có sản phẩm nổi bật.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- New Products -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-fire text-danger"></i> Sản Phẩm Mới</h2>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="btn btn-outline-primary">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($newProducts as $product)
            <div class="col-md-3">
                <div class="card product-card">
                    <div class="position-relative">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image">
                        </a>
                        <span class="product-badge bg-success">Mới</span>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                        </h6>
                        <div class="mb-2">
                            <span class="product-price">{{ number_format($product->price) }}đ</span>
                            @if($product->compare_price)
                                <span class="product-price-old ms-2">{{ number_format($product->compare_price) }}đ</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-star text-warning"></i>
                                <span>{{ number_format($product->averageRating(), 1) }}</span>
                            </div>
                            @if($product->inStock())
                                <button class="btn btn-sm btn-primary" onclick="addToCart({{ $product->id }})">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Chưa có sản phẩm mới.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                </div>
                <h5>Giao hàng nhanh</h5>
                <p class="text-muted">Giao hàng trong 2-3 ngày</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-shield-alt fa-3x text-success"></i>
                </div>
                <h5>Bảo đảm chất lượng</h5>
                <p class="text-muted">Sản phẩm chính hãng 100%</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-undo fa-3x text-warning"></i>
                </div>
                <h5>Đổi trả dễ dàng</h5>
                <p class="text-muted">Đổi trả trong vòng 7 ngày</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="fas fa-headset fa-3x text-info"></i>
                </div>
                <h5>Hỗ trợ 24/7</h5>
                <p class="text-muted">Luôn sẵn sàng hỗ trợ bạn</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* ========================================
   BANNER SECTION WITH SIDEBAR
   ======================================== */

.banner-section {
    background: #f8f9fa;
}

/* ========================================
   CATEGORY SIDEBAR
   ======================================== */

.category-sidebar {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.category-sidebar-header {
    background: var(--primary-green);
    color: white;
    padding: 15px 20px;
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.category-sidebar-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.category-sidebar-list > li {
    position: relative;
    border-bottom: 1px solid #f0f0f0;
}

.category-sidebar-list > li:last-child {
    border-bottom: none;
}

.category-sidebar-list > li > a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s;
    gap: 10px;
    font-size: 14px;
}

.category-sidebar-list > li > a:hover {
    background: #f8f9fa;
    color: var(--primary-green);
    padding-left: 25px;
}

.category-sidebar-list > li > a i.fa-folder {
    font-size: 16px;
}

.category-sidebar-list > li > a span {
    flex: 1;
}

.category-sidebar-list > li > a small {
    color: #999;
    font-size: 12px;
}

.category-sidebar-list > li > a i.fa-chevron-right {
    font-size: 12px;
    color: #999;
}

/* Category Submenu */
.category-submenu {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    width: 600px;
    background: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border-radius: 8px;
    padding: 20px;
    z-index: 100;
    margin-left: 10px;
}

.category-sidebar-list > li:hover .category-submenu {
    display: block;
}

.submenu-header {
    font-weight: 700;
    font-size: 16px;
    color: var(--primary-green);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-green);
}

.submenu-item {
    display: block;
    padding: 8px 12px;
    color: #555;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s;
    font-size: 13px;
}

.submenu-item:hover {
    background: #f8f9fa;
    color: var(--primary-green);
    padding-left: 18px;
}

.submenu-item i {
    margin-right: 6px;
    color: var(--primary-green);
}

.submenu-item small {
    color: #999;
    margin-left: 5px;
}

/* ========================================
   BANNER CAROUSEL - TEXT Ở DƯỚI
   ======================================== */

.banner-carousel {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.banner-carousel .carousel-item {
    height: 400px;
    position: relative;
}

.banner-carousel .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.banner-carousel .carousel-item a {
    text-decoration: none;
}

/* Caption ở phía dưới với gradient từ dưới lên */
.banner-carousel .carousel-caption {
    background: linear-gradient(to top, 
        rgba(0,0,0,0.95) 0%, 
        rgba(0,0,0,0.75) 35%, 
        rgba(0,0,0,0.4) 65%, 
        transparent 100%);
    bottom: 0;
    left: 0;
    right: 0;
    top: auto;
    width: 100%;
    display: flex;
    align-items: flex-end;
    padding: 90px 50px 35px;
    text-align: left;
}

.banner-carousel .banner-content {
    max-width: 700px;
}

.banner-carousel .banner-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 12px;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
    line-height: 1.2;
}

.banner-carousel .banner-description {
    font-size: 1.1rem;
    color: #f8f9fa;
    margin-bottom: 18px;
    text-shadow: 1px 1px 4px rgba(0,0,0,0.8);
    line-height: 1.5;
}

.banner-carousel .btn-banner {
    padding: 10px 28px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: all 0.3s;
    text-decoration: none;
}

.banner-carousel .btn-banner:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,255,255,0.3);
}

/* .banner-carousel .carousel-indicators {
    bottom: 12px;
    z-index: 10;
}

.banner-carousel .carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin: 0 5px;
}

.banner-carousel .carousel-indicators button.active {
    width: 30px;
    border-radius: 5px;
} */

/* Fallback Banner */
.fallback-banner .hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}


/* ========================================
   RESPONSIVE
   ======================================== */

@media (max-width: 991px) {
    .banner-carousel .carousel-item {
        height: 300px;
    }
    
    .banner-carousel .carousel-caption {
        padding: 70px 30px 30px;
    }
    
    .banner-carousel .banner-title {
        font-size: 2rem;
    }
    
    .banner-carousel .banner-description {
        font-size: 1rem;
    }
}

@media (max-width: 767px) {
    .banner-carousel .carousel-item {
        height: 250px;
    }
    
    .banner-carousel .carousel-caption {
        padding: 60px 25px 25px;
    }
    
    .banner-carousel .banner-title {
        font-size: 1.5rem;
    }
    
    .banner-carousel .banner-description {
        font-size: 0.9rem;
    }
}

@media (max-width: 575px) {
    .banner-carousel .carousel-item {
        height: 200px;
    }
    
    .banner-carousel .carousel-caption {
        padding: 50px 20px 20px;
    }
    
    .banner-carousel .banner-title {
        font-size: 1.2rem;
        margin-bottom: 8px;
    }
    
    .banner-carousel .banner-description {
        display: none;
    }
    
    .banner-carousel .btn-banner {
        padding: 8px 20px;
        font-size: 12px;
    }
    
    .banner-carousel .carousel-indicators {
        bottom: 10px;
    }
}

/* Fallback Banner */
.fallback-banner .hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ========================================
   RESPONSIVE
   ======================================== */

@media (max-width: 991px) {
    .category-sidebar {
        margin-bottom: 15px;
    }
    
    .category-submenu {
        display: none !important;
    }
    
    .banner-carousel .carousel-item {
        height: 300px;
    }
    
    .banner-carousel .carousel-caption {
        width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%);
        padding: 20px;
        bottom: 0;
        top: auto;
        right: 0;
    }
    
    .banner-carousel .banner-title {
        font-size: 1.8rem;
    }
    
    .banner-carousel .banner-description {
        font-size: 0.9rem;
    }
}

@media (max-width: 767px) {
    .banner-carousel .carousel-item {
        height: 250px;
    }
    
    .banner-carousel .banner-title {
        font-size: 1.4rem;
    }
    
    .banner-carousel .banner-description {
        display: none;
    }
    
    .category-sidebar-header {
        font-size: 14px;
        padding: 12px 15px;
    }
    
    .category-sidebar-list > li > a {
        padding: 10px 15px;
        font-size: 13px;
    }
}

@media (max-width: 575px) {
    .banner-carousel .carousel-item {
        height: 200px;
    }
    
    .banner-carousel .banner-title {
        font-size: 1.2rem;
    }
    
    .banner-carousel .btn-banner {
        padding: 8px 20px;
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function addToCart(productId) {
    // TODO: Implement add to cart
    alert('Thêm vào giỏ hàng: ' + productId);
}

// Auto play carousel
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('bannerCarousel');
    if (carousel) {
        new bootstrap.Carousel(carousel, {
            interval: 5000,
            ride: 'carousel',
            wrap: true,
            touch: true
        });
    }
});
</script>
@endpush