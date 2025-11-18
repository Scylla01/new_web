@extends('admin.layouts.app')

@section('title', 'Quản Lý Mã Giảm Giá')
@section('page-title', 'Quản Lý Mã Giảm Giá')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-ticket-alt"></i> Danh Sách Mã Giảm Giá</span>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Mã Giảm Giá
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã Code</th>
                        <th>Loại</th>
                        <th>Giá Trị</th>
                        <th>Điều Kiện</th>
                        <th>Sử Dụng</th>
                        <th>Thời Gian</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>
                            <code class="fs-6">{{ $coupon->code }}</code>
                        </td>
                        <td>
                            @if($coupon->type === 'fixed')
                                <span class="badge bg-info">Giảm tiền</span>
                            @else
                                <span class="badge bg-warning">Giảm %</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->type === 'fixed')
                                <strong>{{ number_format($coupon->value) }}đ</strong>
                            @else
                                <strong>{{ $coupon->value }}%</strong>
                            @endif
                        </td>
                        <td>
                            @if($coupon->min_order_amount)
                                Đơn tối thiểu: {{ number_format($coupon->min_order_amount) }}đ<br>
                            @endif
                            @if($coupon->max_discount_amount)
                                Giảm tối đa: {{ number_format($coupon->max_discount_amount) }}đ
                            @endif
                        </td>
                        <td>
                            <strong>{{ $coupon->used_count }}</strong> 
                            @if($coupon->usage_limit)
                                / {{ $coupon->usage_limit }}
                            @else
                                / ∞
                            @endif
                            <br>
                            <small class="text-muted">{{ $coupon->orders_count }} đơn</small>
                        </td>
                        <td>
                            <small>
                                Từ: {{ $coupon->start_date->format('d/m/Y') }}<br>
                                Đến: {{ $coupon->end_date->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    <i class="fas fa-{{ $coupon->is_active ? 'check' : 'times' }}"></i>
                                    {{ $coupon->is_active ? 'Hoạt động' : 'Tắt' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
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
                        <td colspan="9" class="text-center text-muted">Chưa có mã giảm giá nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection