@extends('admin.layouts.app')

@section('title', 'Quản Lý Đánh Giá')
@section('page-title', 'Quản Lý Đánh Giá')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-star"></i> Danh Sách Đánh Giá
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm sản phẩm, khách hàng..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">-- Tất cả sao --</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 sao</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 sao</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 sao</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 sao</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 sao</option>
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
                        <th>Sản Phẩm</th>
                        <th>Khách Hàng</th>
                        <th>Đánh Giá</th>
                        <th>Nội Dung</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Đánh Giá</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $review->product->main_image) }}" 
                                     alt="{{ $review->product->name }}" 
                                     width="40" 
                                     class="me-2 rounded">
                                <div>
                                    <strong>{{ Str::limit($review->product->name, 30) }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $review->user->name }}<br>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </td>
                        <td>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">{{ $review->rating }}/5</small>
                        </td>
                        <td>
                            <div style="max-width: 300px;">
                                {{ Str::limit($review->comment, 100) }}
                            </div>
                        </td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-warning">Chờ duyệt</span>
                            @endif
                        </td>
                        <td>
                            {{ $review->created_at->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group-vertical">
                                @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="mb-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success w-100" title="Duyệt">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="mb-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning w-100" title="Bỏ duyệt">
                                        <i class="fas fa-times"></i> Bỏ duyệt
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100" title="Xóa">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có đánh giá nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection