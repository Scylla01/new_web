@extends('frontend.layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h1 class="text-success mb-3">Đặt Hàng Thành Công!</h1>
                <p class="lead">Cảm ơn bạn đã mua hàng tại Bách Hóa Shop</p>
                <p class="text-muted">Đơn hàng của bạn đã được ghi nhận và đang được xử lý</p>
            </div>

            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong></p>
                            <h4 class="text-primary">{{ $order->order_number }}</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Ngày đặt:</strong></p>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Thông tin người nhận:</h6>
                            <p class="mb-1"><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                            <p class="mb-1"><i class="fas fa-phone"></i> {{ $order->shippingAddress->phone }}</p>
                            <p class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ $order->shippingAddress->fullAddress() }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Phương thức thanh toán:</h6>
                            <p>
                                @if($order->payment_method === 'cod')
                                    <i class="fas fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method === 'bank_transfer')
                                    <i class="fas fa-university"></i> Chuyển khoản ngân hàng
                                @elseif($order->payment_method === 'vnpay')
                                    <i class="fas fa-credit-card"></i> VNPay
                                @else
                                    <i class="fas fa-wallet"></i> MoMo
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Sản Phẩm Đã Đặt</h5>
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
                                    <td>{{ $item->product_name }}</td>
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
                                <tr class="table-success">
                                    <td colspan="3" class="text-end"><strong>TỔNG CỘNG:</strong></td>
                                    <td><h4 class="text-success mb-0">{{ number_format($order->total_amount) }}đ</h4></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Bước tiếp theo:</h5>
                <ul class="mb-0">
                    <li>Chúng tôi sẽ xác nhận đơn hàng và liên hệ với bạn trong thời gian sớm nhất</li>
                    <li>Theo dõi trạng thái đơn hàng tại <a href="{{ route('account.orders') }}">Đơn hàng của tôi</a></li>
                    <li>Đơn hàng sẽ được giao trong vòng 2-3 ngày làm việc</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="text-center mt-4">
                <a href="{{ route('account.orders') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-list"></i> Xem Đơn Hàng
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-home"></i> Về Trang Chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection