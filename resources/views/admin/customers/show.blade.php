@extends('admin.layouts.app')

@section('title', 'Chi Tiết Khách Hàng')
@section('page-title', 'Chi Tiết Khách Hàng')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user"></i> Thông Tin Khách Hàng
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $customer->id }}</td>
                    </tr>
                    <tr>
                        <th>Họ và Tên</th>
                        <td><strong>{{ $customer->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            {{ $customer->email }}
                            @if($customer->email_verified_at)
                                <span class="badge bg-success ms-2">Đã xác thực</span>
                            @else
                                <span class="badge bg-warning ms-2">Chưa xác thực</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Số Điện Thoại</th>
                        <td>{{ $customer->phone ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày Đăng Ký</th>
                        <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập Nhật Lần Cuối</th>
                        <td>{{ $customer->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Addresses -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-map-marker-alt"></i> Địa Chỉ ({{ $customer->addresses->count() }})
            </div>
            <div class="card-body">
                @forelse($customer->addresses as $address)
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $address->full_name }}</strong>
                                @if($address->is_default)
                                    <span class="badge bg-primary">Mặc định</span>
                                @endif
                            </div>
                            <div>{{ $address->phone }}</div>
                        </div>
                        <p class="mb-0 text-muted">{{ $address->fullAddress() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Chưa có địa chỉ nào.</p>
                @endforelse
            </div>
        </div>

        <!-- Orders History -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-shopping-cart"></i> Lịch Sử Đơn Hàng ({{ $customer->orders->count() }})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Ngày Đặt</th>
                                <th>Số SP</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->items->count() }}</td>
                                <td><strong>{{ number_format($order->total_amount) }}đ</strong></td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="badge bg-info">Đã xác nhận</span>
                                    @elseif($order->status === 'shipping')
                                        <span class="badge bg-primary">Đang giao</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="badge bg-success">Hoàn thành</span>
                                    @else
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Chưa có đơn hàng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        @if($customer->reviews->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-star"></i> Đánh Giá ({{ $customer->reviews->count() }})
            </div>
            <div class="card-body">
                @foreach($customer->reviews as $review)
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $review->product->name }}</strong>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                    </div>
                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Statistics -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Thống Kê
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Tổng Đơn Hàng</h6>
                    <h3 class="text-primary">{{ $stats['total_orders'] }}</h3>
                </div>
                <div class="mb-3">
                    <h6>Tổng Chi Tiêu</h6>
                    <h3 class="text-success">{{ number_format($stats['total_spent']) }}đ</h3>
                </div>
                <div class="mb-3">
                    <h6>Đơn Chờ Xử Lý</h6>
                    <h3 class="text-warning">{{ $stats['pending_orders'] }}</h3>
                </div>
                <div class="mb-3">
                    <h6>Đơn Hoàn Thành</h6>
                    <h3 class="text-info">{{ $stats['completed_orders'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-tools"></i> Thao Tác
            </div>
            <div class="card-body">
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Xóa Khách Hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection