@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
<!-- Full Width Background Wrapper -->
<div class="full-bg-wrapper">
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white px-3 py-2 rounded">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

    <div class="row">
        <!-- Left Column - Product Images & Description -->
        <div class="col-lg-6">
            <!-- Product Images -->
            <div class="product-images-container mb-3 bg-white p-3 rounded shadow-sm">
                <!-- Thumbnails Left Side -->
                <div class="thumbnails-column">
                    <div class="thumbnail-item active">
                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                             alt="{{ $product->name }}"
                             onclick="changeMainImage(this.src)">
                    </div>
                    @foreach($product->images as $image)
                    <div class="thumbnail-item">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="{{ $product->name }}"
                             onclick="changeMainImage(this.src)">
                    </div>
                    @endforeach
                </div>

                <!-- Main Image -->
                <div class="main-image-container" id="imageZoomContainer">
                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                         alt="{{ $product->name }}" 
                         id="mainProductImage"
                         class="main-product-image">
                    <div class="zoom-hint">
                        <i class="fas fa-search-plus"></i> Di chuột để phóng to
                    </div>
                    <!-- Zoomed Image Overlay -->
                    <div class="zoom-overlay" id="zoomOverlay"></div>
                </div>
            </div>

            <!-- Product Description Box -->
            <div class="product-description-box shadow-sm">
                <div class="description-header">
                    <h5 class="mb-0"><i class="fas fa-align-left"></i> Mô tả sản phẩm</h5>
                </div>
                <div class="description-body">
                    @if($product->description)
                        <div class="description-content">{{ $product->description }}</div>
                    @else
                        <p class="text-muted mb-0">Chưa có mô tả chi tiết cho sản phẩm này.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Product Info -->
        <div class="col-lg-6">
            <div class="product-info-container bg-white p-4 rounded shadow-sm">
                <!-- Brand Badge -->
                <div class="mb-3">
                    <span class="brand-badge">{{ $product->category->name }}</span>
                </div>

                <!-- Product Title -->
                <h1 class="product-title">{{ $product->name }}</h1>

                <!-- Brand & SKU -->
                <div class="product-meta mb-3 pb-3 border-bottom">
                    <span class="me-3">
                        <strong>Thương hiệu:</strong> 
                        <span class="text-primary">{{ $product->brand ?? 'Đang cập nhật' }}</span>
                    </span>
                    <span>
                        <strong>SKU:</strong> 
                        <code class="sku-code">{{ $product->sku }}</code>
                    </span>
                </div>

                <!-- Price Section -->
                <div class="price-section mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="current-price">{{ number_format($product->price) }}đ</div>
                        @if($product->compare_price)
                            <div class="old-price">{{ number_format($product->compare_price) }}đ</div>
                        @endif
                    </div>
                    @if($product->compare_price)
                        <div class="mb-2">
                            <span class="discount-badge">Tiết kiệm {{ number_format($product->compare_price - $product->price) }}đ</span>
                        </div>
                    @endif
                    <div class="tax-note">
                        <small><i class="fas fa-info-circle"></i> Đã bao gồm thuế</small>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="stock-section mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="stock-label">Kho:</span>
                        @if($product->inStock())
                            <span class="stock-status in-stock">
                                <i class="fas fa-check-circle"></i> Còn hàng
                            </span>
                        @else
                            <span class="stock-status out-stock">
                                <i class="fas fa-times-circle"></i> Hết hàng
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Quantity & Add to Cart -->
                @if($product->inStock())
                <div class="quantity-cart-section mb-4 pb-3 border-bottom">
                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="quantity-controls mb-3">
                            <label class="quantity-label">Số lượng:</label>
                            <div class="quantity-input-group">
                                <button type="button" class="qty-btn qty-minus" onclick="decreaseQty()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" 
                                       min="1" max="{{ $product->stock_quantity }}" 
                                       class="qty-input" readonly>
                                <button type="button" class="qty-btn qty-plus" onclick="increaseQty()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-add-cart" onclick="document.getElementById('addToCartForm').submit()">
                                THÊM VÀO GIỎ
                            </button>
                            <button type="button" class="btn-buy-now" onclick="buyNow()">
                                ĐẶT HÀNG NGAY
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-triangle"></i> Sản phẩm tạm thời hết hàng
                </div>
                @endif

                <!-- Product Features -->
                <div class="product-features mb-4 pb-3 border-bottom">
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Cam kết sản phẩm chính hãng</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Giao hàng COD toàn quốc</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo-alt"></i>
                        <span>Hỗ trợ đổi trả nếu sản phẩm lỗi</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-tags"></i>
                        <span>Giá sỉ tốt dành cho các shop & đại lý</span>
                    </div>
                </div>

                <!-- Customer Reviews Summary -->
                <div class="reviews-summary">
                    <h5 class="mb-3">Đánh giá của Khách Hàng</h5>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $product->averageRating() ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <div class="rating-text">
                            @if($product->reviews->count() > 0)
                                <strong>{{ number_format($product->averageRating(), 1) }}/5</strong>
                                <span class="text-muted">({{ $product->reviews->count() }} đánh giá)</span>
                            @else
                                <span class="text-muted">Hãy là người đầu tiên viết đánh giá</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="product-tabs bg-white rounded shadow-sm">
                <ul class="nav nav-tabs custom-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#reviews">
                            <i class="fas fa-star"></i> Đánh giá ({{ $product->reviews->count() }})
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#shipping">
                            <i class="fas fa-truck"></i> Chính sách giao hàng
                        </button>
                    </li>
                </ul>

                <div class="tab-content custom-tab-content">
                    <!-- Reviews Tab -->
                    <div class="tab-pane fade show active" id="reviews">
                        @auth
                        <!-- Write Review Form -->
                        <div class="write-review-section mb-4">
                            <h5 class="mb-3">Viết đánh giá của bạn</h5>
                            <form action="{{ route('reviews.store') }}" method="POST" class="review-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Đánh giá của bạn:</label>
                                    <div class="rating-input">
                                        <input type="radio" name="rating" value="5" id="star5" required>
                                        <label for="star5"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="4" id="star4">
                                        <label for="star4"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="3" id="star3">
                                        <label for="star3"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="2" id="star2">
                                        <label for="star2"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="1" id="star1">
                                        <label for="star1"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nhận xét của bạn:</label>
                                    <textarea name="comment" class="form-control" rows="4" 
                                              placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Vui lòng <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để viết đánh giá
                        </div>
                        @endauth

                        <!-- Reviews List -->
                        <div class="reviews-list">
                            @forelse($product->reviews()->where('is_approved', true)->latest()->get() as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="reviewer-name">{{ $review->user->name }}</strong>
                                            <div class="review-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="review-content">
                                    {{ $review->comment }}
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Shipping Tab -->
                    <div class="tab-pane fade" id="shipping">
                        <div class="shipping-info">
                            <h5 class="mb-3">Chính sách giao hàng và đổi trả</h5>
                            <ul>
                                <li>Giao hàng toàn quốc, nhận hàng trong 2-5 ngày</li>
                                <li>Miễn phí vận chuyển cho đơn hàng từ 500.000đ</li>
                                <li>Kiểm tra hàng trước khi thanh toán</li>
                                <li>Đổi trả trong vòng 7 ngày nếu sản phẩm lỗi</li>
                                <li>Hỗ trợ đổi size, đổi màu trong 3 ngày</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products-section mt-5">
        <h3 class="section-title mb-4">
            <i class="fas fa-box-open"></i> Sản phẩm liên quan
        </h3>
        <div class="row g-3">
            @foreach($relatedProducts as $related)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <a href="{{ route('products.show', $related->slug) }}">
                            <img src="{{ asset('storage/' . $related->main_image) }}" 
                                 alt="{{ $related->name }}" 
                                 class="card-img-top product-card-img">
                        </a>
                        @if($related->compare_price)
                        <span class="product-badge">-{{ $related->discountPercent() }}%</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="product-card-title">
                            <a href="{{ route('products.show', $related->slug) }}">
                                {{ Str::limit($related->name, 60) }}
                            </a>
                        </h6>
                        <div class="product-card-price mb-2">
                            <span class="price-current">{{ number_format($related->price) }}đ</span>
                            @if($related->compare_price)
                            <span class="price-old">{{ number_format($related->compare_price) }}đ</span>
                            @endif
                        </div>
                        <button class="btn btn-sm btn-outline-primary w-100" onclick="quickAddToCart({{ $related->id }})">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Product Images Container */
.product-images-container {
    display: flex;
    gap: 15px;
    margin-bottom: 0;
}

/* Product Description Box */
.product-description-box {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
}

.description-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.description-header h5 {
    color: #333;
    font-size: 16px;
    font-weight: 600;
}

.description-header i {
    color: #2d5f3f;
    margin-right: 8px;
}

.description-body {
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
}

.description-body::-webkit-scrollbar {
    width: 6px;
}

.description-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.description-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.description-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.thumbnails-column {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 80px;
}

.thumbnail-item {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 5px;
    cursor: pointer;
    transition: all 0.3s;
    overflow: hidden;
}

.thumbnail-item.active,
.thumbnail-item:hover {
    border-color: #2d5f3f;
    box-shadow: 0 2px 8px rgba(45, 95, 63, 0.2);
}

.thumbnail-item img {
    width: 100%;
    height: 70px;
    object-fit: cover;
    border-radius: 4px;
}

.main-image-container {
    flex: 1;
    position: relative;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    background: #f9f9f9;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    cursor: zoom-in;
}

.main-product-image {
    max-width: 100%;
    max-height: 500px;
    object-fit: contain;
    transition: opacity 0.3s;
}

.main-image-container:hover .main-product-image {
    opacity: 0;
}

.zoom-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-size: 200%;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s;
}

.main-image-container:hover .zoom-overlay {
    opacity: 1;
}

.zoom-hint {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
}

/* Product Info */
.product-info-container {
    padding: 0;
}

.brand-badge {
    background: #2d5f3f;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}

.product-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    line-height: 1.4;
    margin-bottom: 15px;
}

.product-meta {
    font-size: 14px;
    color: #666;
}

.sku-code {
    background: #f0f0f0;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 13px;
}

/* Price Section */
.price-section {
    background: transparent;
    padding: 0;
    border-radius: 0;
    border: none;
}

.current-price {
    font-size: 28px;
    font-weight: 700;
    color: #e74c3c;
}

.old-price {
    font-size: 16px;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #e74c3c;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.tax-note {
    color: #666;
}

/* Stock Section */
.stock-section {
    display: block;
}

.stock-label {
    font-weight: 600;
    color: #333;
    display: inline-block;
    margin-right: 10px;
}

.stock-status {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.stock-status.in-stock {
    background: #d4edda;
    color: #155724;
}

.stock-status.out-stock {
    background: #f8d7da;
    color: #721c24;
}

/* Quantity Controls */
.quantity-controls {
    display: flex;
    align-items: center;
    gap: 15px;
}

.quantity-label {
    font-weight: 600;
    color: #333;
    margin: 0;
}

.quantity-input-group {
    display: flex;
    align-items: center;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    background: white;
    border: none;
    width: 40px;
    height: 40px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    color: #666;
}

.qty-btn:hover {
    background: #f0f0f0;
    color: #2d5f3f;
}

.qty-input {
    width: 60px;
    height: 40px;
    text-align: center;
    border: none;
    border-left: 2px solid #e0e0e0;
    border-right: 2px solid #e0e0e0;
    font-size: 16px;
    font-weight: 600;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-add-cart {
    flex: 1;
    background: white;
    border: 2px solid #2d5f3f;
    color: #2d5f3f;
    padding: 15px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-add-cart:hover {
    background: #2d5f3f;
    color: white;
}

.btn-buy-now {
    flex: 1;
    background: #e74c3c;
    border: 2px solid #e74c3c;
    color: white;
    padding: 15px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-buy-now:hover {
    background: #c0392b;
    border-color: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
}

/* Product Features */
.product-features {
    background: transparent;
    padding: 0;
    border-radius: 0;
    margin-top: 0;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    font-size: 14px;
    color: #555;
}

.feature-item i {
    color: #2d5f3f;
    font-size: 16px;
    width: 20px;
}

/* Reviews Summary */
.reviews-summary {
    border-top: none;
    padding-top: 0;
}

.rating-stars i {
    font-size: 20px;
}

/* Product Tabs */
.custom-tabs {
    border-bottom: 2px solid #e0e0e0;
}

.custom-tabs .nav-link {
    border: none;
    color: #666;
    font-weight: 500;
    padding: 15px 25px;
    background: transparent;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.custom-tabs .nav-link:hover {
    color: #2d5f3f;
}

.custom-tabs .nav-link.active {
    color: #2d5f3f;
    border-bottom-color: #2d5f3f;
    background: transparent;
}

.custom-tab-content {
    padding: 30px 20px;
    background: white;
}

.description-content {
    line-height: 1.8;
    color: #555;
    white-space: pre-line;
}

/* Review Form */
.write-review-section {
    background: #f9f9f9;
    padding: 25px;
    border-radius: 10px;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    color: #ddd;
    font-size: 28px;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input:checked ~ label {
    color: #ffc107;
}

/* Reviews List */
.review-item {
    padding: 20px 0;
    border-bottom: 1px solid #e0e0e0;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}

.reviewer-info {
    display: flex;
    gap: 15px;
    align-items: center;
}

.reviewer-avatar {
    width: 45px;
    height: 45px;
    background: #2d5f3f;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
}

.reviewer-name {
    display: block;
    color: #333;
    margin-bottom: 5px;
}

.review-rating i {
    font-size: 14px;
}

.review-content {
    color: #555;
    line-height: 1.6;
    margin-left: 60px;
}

/* Related Products */
.section-title {
    font-size: 22px;
    font-weight: 600;
    color: #333;
    padding-bottom: 10px;
    border-bottom: 2px solid #2d5f3f;
}

.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.product-card-img {
    height: 200px;
    object-fit: cover;
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.product-card-title {
    font-size: 14px;
    margin-bottom: 10px;
}

.product-card-title a {
    color: #333;
    text-decoration: none;
}

.product-card-title a:hover {
    color: #2d5f3f;
}

.product-card-price {
    display: flex;
    align-items: center;
    gap: 10px;
}

.price-current {
    font-size: 18px;
    font-weight: 700;
    color: #e74c3c;
}

.price-old {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
}

/* Responsive */
@media (max-width: 768px) {
    .product-images-container {
        flex-direction: column-reverse;
    }
    
    .thumbnails-column {
        flex-direction: row;
        width: 100%;
        overflow-x: auto;
    }
    
    .thumbnail-item {
        min-width: 70px;
    }
    
    .product-title {
        font-size: 20px;
    }
    
    .current-price {
        font-size: 26px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Zoom Functionality
    const imageContainer = document.getElementById('imageZoomContainer');
    const mainImage = document.getElementById('mainProductImage');
    const zoomOverlay = document.getElementById('zoomOverlay');

    if (imageContainer && mainImage && zoomOverlay) {
        // Set initial background image
        zoomOverlay.style.backgroundImage = 'url("' + mainImage.src + '")';
        
        imageContainer.addEventListener('mousemove', function(e) {
            const rect = imageContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            zoomOverlay.style.backgroundPosition = x + '% ' + y + '%';
        });
        
        imageContainer.addEventListener('mouseleave', function() {
            zoomOverlay.style.opacity = '0';
        });
        
        imageContainer.addEventListener('mouseenter', function() {
            zoomOverlay.style.opacity = '1';
        });
    }
});

function changeMainImage(src) {
    const mainImage = document.getElementById('mainProductImage');
    const zoomOverlay = document.getElementById('zoomOverlay');
    
    if (mainImage) {
        mainImage.src = src;
    }
    
    if (zoomOverlay) {
        zoomOverlay.style.backgroundImage = 'url("' + src + '")';
    }
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item').forEach(function(item) {
        item.classList.remove('active');
    });
    
    if (event && event.target) {
        const thumbnail = event.target.closest('.thumbnail-item');
        if (thumbnail) {
            thumbnail.classList.add('active');
        }
    }
}

function increaseQty() {
    const input = document.getElementById('quantity');
    if (input) {
        const max = parseInt(input.max);
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    if (input) {
        const min = parseInt(input.min);
        const current = parseInt(input.value);
        if (current > min) {
            input.value = current - 1;
        }
    }
}

function buyNow() {
    const form = document.getElementById('addToCartForm');
    if (!form) return;
    
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
        return;
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            window.location.href = '{{ route("checkout.index") }}';
        } else {
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
    });
}

function quickAddToCart(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
        return;
    }
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
            }
            alert('Đã thêm sản phẩm vào giỏ hàng!');
        } else {
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
    });
}
</script>
@endpush