@extends('frontend.layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Đơn hàng của tôi</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Tài Khoản</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                    </a>
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-edit"></i> Thông tin tài khoản
                    </a>
                    <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt"></i> Địa chỉ
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Đơn Hàng Của Tôi</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Đơn hàng: {{ $order->order_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div>
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
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Order Items Preview -->
                                        @foreach($order->items->take(3) as $item)
                                        <div class="d-flex mb-2">
                                            @if($item->product)
                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 width="60" 
                                                 class="me-3 rounded">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong><br>
                                                <small class="text-muted">SL: {{ $item->quantity }} x {{ number_format($item->price) }}đ</small>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                        <small class="text-muted">Và {{ $order->items->count() - 3 }} sản phẩm khác...</small>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <p class="mb-1">
                                            <strong>Tổng tiền:</strong><br>
                                            <span class="text-primary fs-5">{{ number_format($order->total_amount) }}đ</span>
                                        </p>
                                        <p class="mb-3">
                                            @if($order->payment_status === 'unpaid')
                                                <span class="badge bg-warning">Chưa thanh toán</span>
                                            @elseif($order->payment_status === 'paid')
                                                <span class="badge bg-success">Đã thanh toán</span>
                                            @else
                                                <span class="badge bg-danger">Đã hoàn tiền</span>
                                            @endif
                                        </p>
                                        <a href="{{ route('account.orders.detail', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                        @if($order->canCancel())
                                        <form action="{{ route('account.orders.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i> Hủy
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-5x text-muted mb-3"></i>
                            <h5>Bạn chưa có đơn hàng nào</h5>
                            <p class="text-muted">Hãy mua sắm ngay để có đơn hàng đầu tiên!</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection