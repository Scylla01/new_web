@extends('admin.layouts.app')

@section('title', 'Chi Tiết Mã Giảm Giá')
@section('page-title', 'Chi Tiết Mã Giảm Giá')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-ticket-alt"></i> Thông Tin Mã Giảm Giá</span>
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Chỉnh Sửa
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="text-center p-4 bg-light rounded">
                            <h1 class="display-4 fw-bold text-primary">{{ $coupon->code }}</h1>
                            <p class="lead mb-0">
                                @if($coupon->type === 'fixed')
                                    Giảm <strong class="text-danger">{{ number_format($coupon->value) }}đ</strong>
                                @else
                                    Giảm <strong class="text-danger">{{ $coupon->value }}%</strong>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $coupon->id }}</td>
                    </tr>
                    <tr>
                        <th>Mã Code</th>
                        <td><code class="fs-5">{{ $coupon->code }}</code></td>
                    </tr>
                    <tr>
                        <th>Mô Tả</th>
                        <td>{{ $coupon->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Loại Giảm Giá</th>
                        <td>
                            @if($coupon->type === 'fixed')
                                <span class="badge bg-info">Giảm tiền cố định</span>
                            @else
                                <span class="badge bg-warning">Giảm theo phần trăm</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Giá Trị</th>
                        <td>
                            @if($coupon->type === 'fixed')
                                <strong class="text-danger fs-5">{{ number_format($coupon->value) }}đ</strong>
                            @else
                                <strong class="text-danger fs-5">{{ $coupon->value }}%</strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Đơn Hàng Tối Thiểu</th>
                        <td>
                            @if($coupon->min_order_amount)
                                {{ number_format($coupon->min_order_amount) }}đ
                            @else
                                <span class="text-muted">Không giới hạn</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Giảm Tối Đa</th>
                        <td>
                            @if($coupon->max_discount_amount)
                                {{ number_format($coupon->max_discount_amount) }}đ
                            @else
                                <span class="text-muted">Không giới hạn</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Giới Hạn Sử Dụng</th>
                        <td>
                            @if($coupon->usage_limit)
                                {{ $coupon->usage_limit }} lần
                            @else
                                <span class="text-muted">Không giới hạn</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Đã Sử Dụng</th>
                        <td><strong class="text-primary">{{ $coupon->used_count }}</strong> lần</td>
                    </tr>
                    <tr>
                        <th>Thời Gian</th>
                        <td>
                            Từ: <strong>{{ $coupon->start_date->format('d/m/Y H:i') }}</strong><br>
                            Đến: <strong>{{ $coupon->end_date->format('d/m/Y H:i') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng Thái</th>
                        <td>
                            @if($coupon->is_active)
                                <span class="badge bg-success">Đang hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Đã tắt</span>
                            @endif
                            
                            @if($coupon->isValid())
                                <span class="badge bg-success ms-2">Còn hạn</span>
                            @else
                                <span class="badge bg-danger ms-2">Hết hạn / Đã dùng hết</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày Tạo</th>
                        <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập Nhật</th>
                        <td>{{ $coupon->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Orders using this coupon -->
        @if($coupon->orders->count() > 0)
        <div class="card">
            <div class="card-header">
                <i class="fas fa-shopping-cart"></i> Đơn Hàng Đã Sử Dụng ({{ $coupon->orders->count() }})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Khách Hàng</th>
                                <th>Tổng Tiền</th>
                                <th>Giảm Giá</th>
                                <th>Ngày Đặt</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupon->orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td class="text-danger">-{{ number_format($order->discount_amount) }}đ</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Thống Kê
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Tổng Đơn Sử Dụng</h6>
                    <h3 class="text-primary">{{ $coupon->orders->count() }}</h3>
                </div>
                <div class="mb-3">
                    <h6>Tổng Tiền Đã Giảm</h6>
                    <h3 class="text-danger">{{ number_format($coupon->orders->sum('discount_amount')) }}đ</h3>
                </div>
                <div class="mb-3">
                    <h6>Số Lần Còn Lại</h6>
                    <h3 class="text-info">
                        @if($coupon->usage_limit)
                            {{ max(0, $coupon->usage_limit - $coupon->used_count) }}
                        @else
                            ∞
                        @endif
                    </h3>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-tools"></i> Thao Tác
            </div>
            <div class="card-body">
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit"></i> Chỉnh Sửa
                </a>
                <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-{{ $coupon->is_active ? 'secondary' : 'success' }} w-100">
                        <i class="fas fa-{{ $coupon->is_active ? 'times' : 'check' }}"></i> 
                        {{ $coupon->is_active ? 'Tắt Mã' : 'Kích Hoạt' }}
                    </button>
                </form>
                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Xóa Mã
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection