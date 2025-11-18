@extends('frontend.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.orders') }}">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Trạng Thái Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <div class="mb-2">
                                <i class="fas fa-check-circle fa-2x {{ in_array($order->status, ['pending', 'confirmed', 'shipping', 'delivered']) ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <small>Đặt hàng</small>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <i class="fas fa-clipboard-check fa-2x {{ in_array($order->status, ['confirmed', 'shipping', 'delivered']) ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <small>Xác nhận</small>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <i class="fas fa-shipping-fast fa-2x {{ in_array($order->status, ['shipping', 'delivered']) ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <small>Đang giao</small>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <i class="fas fa-box-open fa-2x {{ $order->status === 'delivered' ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <small>Hoàn thành</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong></p>
                            <h5 class="text-primary">{{ $order->order_number }}</h5>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày đặt:</strong></p>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($order->note)
                    <div class="alert alert-info">
                        <strong>Ghi chú:</strong> {{ $order->note }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Sản Phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product)
                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 width="60" 
                                                 class="me-3 rounded">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product)
                                                    <br><a href="{{ route('products.show', $item->product->slug) }}" class="btn btn-sm btn-outline-primary mt-1">Xem sản phẩm</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>{{ number_format($item->subtotal) }}đ</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tạm tính:</strong></td>
                                    <td><strong>{{ number_format($order->subtotal) }}đ</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                    <td><strong>{{ number_format($order->shipping_fee) }}đ</strong></td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Giảm giá:</strong></td>
                                    <td><strong class="text-danger">-{{ number_format($order->discount_amount) }}đ</strong></td>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <td colspan="3" class="text-end"><strong>TỔNG CỘNG:</strong></td>
                                    <td><h4 class="text-primary mb-0">{{ number_format($order->total_amount) }}đ</h4></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Địa Chỉ Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                    <p class="mb-1"><i class="fas fa-phone"></i> {{ $order->shippingAddress->phone }}</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ $order->shippingAddress->fullAddress() }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Summary -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Tóm Tắt</h5>
                </div>
                <div class="card-body">
                    <p><strong>Trạng thái:</strong><br>
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
                    </p>
                    <p><strong>Thanh toán:</strong><br>
                        @if($order->payment_method === 'cod')
                            COD
                        @elseif($order->payment_method === 'bank_transfer')
                            Chuyển khoản
                        @elseif($order->payment_method === 'vnpay')
                            VNPay
                        @else
                            MoMo
                        @endif
                    </p>
                    <p><strong>Trạng thái TT:</strong><br>
                        @if($order->payment_status === 'unpaid')
                            <span class="badge bg-warning">Chưa thanh toán</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-danger">Đã hoàn tiền</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Actions -->
            @if($order->canCancel())
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Hành Động</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('account.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times"></i> Hủy Đơn Hàng
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Help -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Cần Hỗ Trợ?</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><i class="fas fa-phone"></i> Hotline: 1900 1234</p>
                    <p class="mb-0"><i class="fas fa-envelope"></i> support@bachhoashop.com</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('account.orders') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>
@endsection