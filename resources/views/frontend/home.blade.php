@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Chào mừng đến với Bách Hóa Shop</h1>
                <p class="lead mb-4">Mua sắm dễ dàng, tiện lợi với hàng ngàn sản phẩm chất lượng</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                </a>
            </div>
            <div class="col-md-6 text-center">
                <i class="fas fa-shopping-cart" style="font-size: 200px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Danh Mục Sản Phẩm</h2>
        <div class="text-center">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-badge text-decoration-none text-dark">
                    <i class="fas fa-folder"></i> {{ $category->name }} ({{ $category->products_count }})
                </a>
            @endforeach
        </div>
    </div>
</section>

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

@push('scripts')
<script>
    function addToCart(productId) {
        // TODO: Implement add to cart
        alert('Thêm vào giỏ hàng: ' + productId);
    }
</script>
@endpush