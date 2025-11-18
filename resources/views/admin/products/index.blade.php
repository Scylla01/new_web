@extends('admin.layouts.app')

@section('title', 'Quản Lý Sản Phẩm')
@section('page-title', 'Quản Lý Sản Phẩm')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-box"></i> Danh Sách Sản Phẩm</span>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Sản Phẩm
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên sản phẩm, SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Lọc
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>SKU</th>
                        <th>Danh Mục</th>
                        <th>Giá</th>
                        <th>Tồn Kho</th>
                        <th>Trạng Thái</th>
                        <th>Nổi Bật</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" width="60" height="60" style="object-fit: cover; border-radius: 5px;">
                        </td>
                        <td>
                            <strong>{{ Str::limit($product->name, 40) }}</strong>
                            @if($product->hasDiscount())
                                <br><small class="text-danger">Giảm {{ $product->discountPercent() }}%</small>
                            @endif
                        </td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                        <td>
                            <strong>{{ number_format($product->price) }}đ</strong>
                            @if($product->compare_price)
                                <br><small class="text-muted"><del>{{ number_format($product->compare_price) }}đ</del></small>
                            @endif
                        </td>
                        <td>
                            @if($product->stock_quantity < 10)
                                <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    <i class="fas fa-{{ $product->is_active ? 'check' : 'times' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $product->is_featured ? 'btn-warning' : 'btn-outline-secondary' }}">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">Không có sản phẩm nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection