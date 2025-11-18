@extends('admin.layouts.app')

@section('title', 'Chi Tiết Danh Mục')
@section('page-title', 'Chi Tiết Danh Mục')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-info-circle"></i> Thông Tin Danh Mục</span>
                <div>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Chỉnh Sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên Danh Mục</th>
                        <td><strong>{{ $category->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Mô Tả</th>
                        <td>{{ $category->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Danh Mục Cha</th>
                        <td>
                            @if($category->parent)
                                <span class="badge bg-secondary">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-muted">Danh mục gốc</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Hình Ảnh</th>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="max-width: 300px; border-radius: 5px;">
                            @else
                                <span class="text-muted">Chưa có hình ảnh</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng Thái</th>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Thứ Tự</th>
                        <td>{{ $category->sort_order }}</td>
                    </tr>
                    <tr>
                        <th>Ngày Tạo</th>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập Nhật Lần Cuối</th>
                        <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Products in this category -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-box"></i> Sản Phẩm Trong Danh Mục ({{ $category->products->count() }})
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hình</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Giá</th>
                                    <th>Tồn Kho</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" width="50" style="border-radius: 5px;">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ number_format($product->price) }}đ</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Đang bán</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Chưa có sản phẩm nào trong danh mục này.</p>
                @endif
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
                    <h6>Tổng Sản Phẩm</h6>
                    <h3 class="text-primary">{{ $category->products()->count() }}</h3>
                </div>
                <div class="mb-3">
                    <h6>Sản Phẩm Đang Bán</h6>
                    <h3 class="text-success">{{ $category->products()->where('is_active', true)->count() }}</h3>
                </div>
                <div class="mb-3">
                    <h6>Danh Mục Con</h6>
                    <h3 class="text-info">{{ $category->children()->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-tools"></i> Thao Tác
            </div>
            <div class="card-body">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit"></i> Chỉnh Sửa
                </a>
                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }} w-100">
                        <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i> 
                        {{ $category->is_active ? 'Ẩn Danh Mục' : 'Hiện Danh Mục' }}
                    </button>
                </form>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Xóa Danh Mục
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection