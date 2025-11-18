@extends('admin.layouts.app')

@section('title', 'Quản Lý Đơn Hàng')
@section('page-title', 'Quản Lý Đơn Hàng')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-shopping-cart"></i> Danh Sách Đơn Hàng
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm mã đơn, khách hàng..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">-- Thanh toán --</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" placeholder="Từ ngày" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" placeholder="Đến ngày" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>SĐT</th>
                        <th>Sản Phẩm</th>
                        <th>Tổng Tiền</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Đặt</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong>{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            {{ $order->user->name }}<br>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td>{{ $order->shippingAddress->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $order->items->count() }} SP</span>
                        </td>
                        <td>
                            <strong>{{ number_format($order->total_amount) }}đ</strong>
                        </td>
                        <td>
                            @if($order->payment_status === 'unpaid')
                                <span class="badge bg-warning">Chưa TT</span>
                            @elseif($order->payment_status === 'paid')
                                <span class="badge bg-success">Đã TT</span>
                            @else
                                <span class="badge bg-danger">Hoàn tiền</span>
                            @endif
                            <br>
                            <small class="text-muted">
                                @if($order->payment_method === 'cod')
                                    COD
                                @elseif($order->payment_method === 'bank_transfer')
                                    Chuyển khoản
                                @elseif($order->payment_method === 'vnpay')
                                    VNPay
                                @else
                                    MoMo
                                @endif
                            </small>
                        </td>
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
                            {{ $order->created_at->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Không có đơn hàng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection