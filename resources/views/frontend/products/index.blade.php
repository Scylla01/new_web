@extends('frontend.layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ Lọc</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm..." value="{{ request('search') }}">
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->products_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Khoảng giá</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="price_from" class="form-control form-control-sm" placeholder="Từ" value="{{ request('price_from') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="price_to" class="form-control form-control-sm" placeholder="Đến" value="{{ request('price_to') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="5" id="rating5" {{ request('rating') == 5 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating5">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="4" id="rating4" {{ request('rating') == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating4">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i> trở lên
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="3" id="rating3" {{ request('rating') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating3">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i> trở lên
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-search"></i> Áp dụng
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo"></i> Xóa bộ lọc
                        </a>
                    </form>
                </div>
            </div>

            <!-- Featured Categories -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Danh Mục Nổi Bật</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($categories->take(8) as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                        <span class="badge bg-primary rounded-pill">{{ $cat->products_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <!-- Toolbar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">
                        @if(request('search'))
                            Kết quả tìm kiếm: "{{ request('search') }}"
                        @elseif(request('category'))
                            {{ $categories->find(request('category'))->name ?? 'Sản phẩm' }}
                        @else
                            Tất cả sản phẩm
                        @endif
                    </h4>
                    <small class="text-muted">{{ $products->total() }} sản phẩm</small>
                </div>
                <div>
                    <form action="{{ route('products.index') }}" method="GET" class="d-inline">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                            <option value="">Sắp xếp</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Products -->
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4">
                        <div class="card product-card h-100">
                            <div class="position-relative">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image">
                                </a>
                                @if($product->hasDiscount())
                                    <span class="product-badge">-{{ $product->discountPercent() }}%</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="product-badge bg-warning" style="top: 50px;">
                                        <i class="fas fa-star"></i> Nổi bật
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                <small class="text-muted">{{ $product->category->name }}</small>
                                <h6 class="card-title mt-1">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($product->name, 60) }}
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
                                            <i class="fas fa-cart-plus"></i> Thêm
                                        </button>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                    <h4>Không tìm thấy sản phẩm</h4>
                    <p class="text-muted">Vui lòng thử lại với từ khóa khác hoặc xóa bộ lọc</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Xem tất cả sản phẩm
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addToCart(productId) {
        // TODO: Implement add to cart
        alert('Thêm vào giỏ hàng: ' + productId);
    }
</script>
@endpush