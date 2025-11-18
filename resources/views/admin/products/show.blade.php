@extends('admin.layouts.app')

@section('title', 'Chi Tiết Sản Phẩm')
@section('page-title', 'Chi Tiết Sản Phẩm')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-box"></i> Thông Tin Sản Phẩm</span>
                <div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Chỉnh Sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $product->name }}</h3>
                        <p class="text-muted">{{ $product->category->name }}</p>
                        
                        <div class="mb-2">
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Đang bán' : 'Ẩn' }}
                            </span>
                            @if($product->is_featured)
                                <span class="badge bg-warning">Nổi bật</span>
                            @endif
                            @if($product->hasDiscount())
                                <span class="badge bg-danger">Giảm {{ $product->discountPercent() }}%</span>
                            @endif
                        </div>

                        <h4 class="text-primary">{{ number_format($product->price) }}đ</h4>
                        @if($product->compare_price)
                            <p class="text-muted"><del>{{ number_format($product->compare_price) }}đ</del></p>
                        @endif

                        <table class="table table-sm">
                            <tr>
                                <th width="150">SKU:</th>
                                <td><code>{{ $product->sku }}</code></td>
                            </tr>
                            <tr>
                                <th>Tồn kho:</th>
                                <td>
                                    <span class="badge bg-{{ $product->stock_quantity > 10 ? 'success' : 'danger' }}">
                                        {{ $product->stock_quantity }} sản phẩm
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Lượt xem:</th>
                                <td>{{ $product->view_count }}</td>
                            </tr>
                            <tr>
                                <th>Đánh giá:</th>
                                <td>
                                    <i class="fas fa-star text-warning"></i> 
                                    {{ number_format($product->averageRating(), 1) }} 
                                    ({{ $product->reviews->count() }} đánh giá)
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5>Mô Tả Sản Phẩm</h5>
                <p>{{ $product->description ?? 'Chưa có mô tả' }}</p>

                @if($product->images->count() > 0)
                    <hr>
                    <h5>Hình Ảnh Sản Phẩm</h5>
                    <div class="row g-2">
                        @foreach($product->images as $image)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-star"></i> Đánh Giá ({{ $product->reviews->count() }})
            </div>
            <div class="card-body">
                @forelse($product->reviews as $review)
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $review->user->name }}</strong>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                    @if(!$review->is_approved)
                        <span class="badge bg-warning">Chờ duyệt</span>
                    @endif
                </div>
                @empty
                <p class="text-muted text-center">Chưa có đánh giá nào.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Thống Kê
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Tổng Đã Bán</h6>
                    <h3 class="text-primary">{{ $product->orderItems->sum('quantity') }}</h3>
                </div>
                <div class="mb-3">
                    <h6>Doanh Thu</h6>
                    <h3 class="text-success">{{ number_format($product->orderItems->sum('subtotal')) }}đ</h3>
                </div>
                <div class="mb-3">
                    <h6>Số Đơn Hàng</h6>
                    <h3 class="text-info">{{ $product->orderItems->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Chi Tiết
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $product->id }}</p>
                <p><strong>Slug:</strong> <code>{{ $product->slug }}</code></p>
                <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
                <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-tools"></i> Thao Tác
            </div>
            <div class="card-body">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit"></i> Chỉnh Sửa
                </a>
                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-{{ $product->is_active ? 'secondary' : 'success' }} w-100">
                        <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i> 
                        {{ $product->is_active ? 'Ẩn Sản Phẩm' : 'Hiện Sản Phẩm' }}
                    </button>
                </form>
                <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-{{ $product->is_featured ? 'outline-warning' : 'warning' }} w-100">
                        <i class="fas fa-star"></i> 
                        {{ $product->is_featured ? 'Bỏ Nổi Bật' : 'Đặt Nổi Bật' }}
                    </button>
                </form>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Xóa Sản Phẩm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection