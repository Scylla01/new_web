@extends('admin.layouts.app')

@section('title', 'Chi Tiết Đơn Hàng')
@section('page-title', 'Chi Tiết Đơn Hàng #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Order Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Thông Tin Đơn Hàng
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p>
                            <strong>Trạng thái:</strong>
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
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Thanh toán:</strong>
                            @if($order->payment_method === 'cod')
                                COD (Thanh toán khi nhận hàng)
                            @elseif($order->payment_method === 'bank_transfer')
                                Chuyển khoản ngân hàng
                            @elseif($order->payment_method === 'vnpay')
                                VNPay
                            @else
                                MoMo
                            @endif
                        </p>
                        <p>
                            <strong>Trạng thái thanh toán:</strong>
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

                @if($order->note)
                <div class="alert alert-info">
                    <strong>Ghi chú:</strong> {{ $order->note }}
                </div>
                @endif

                @if($order->coupon)
                <div class="alert alert-success">
                    <strong>Mã giảm giá:</strong> {{ $order->coupon->code }} 
                    (-{{ number_format($order->discount_amount) }}đ)
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-box"></i> Sản Phẩm Đã Đặt
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th>Đơn Giá</th>
                                <th>Số Lượng</th>
                                <th>Thành Tiền</th>
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
                                                 width="50" 
                                                 class="me-2 rounded">
                                        @endif
                                        <div>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->product)
                                                <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
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
                                <td><strong class="text-primary fs-5">{{ number_format($order->total_amount) }}đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user"></i> Thông Tin Khách Hàng
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Người đặt hàng:</h6>
                        <p>
                            <strong>{{ $order->user->name }}</strong><br>
                            Email: {{ $order->user->email }}<br>
                            SĐT: {{ $order->user->phone ?? 'Chưa cập nhật' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Địa chỉ giao hàng:</h6>
                        <p>
                            <strong>{{ $order->shippingAddress->full_name }}</strong><br>
                            SĐT: {{ $order->shippingAddress->phone }}<br>
                            {{ $order->shippingAddress->address_detail }}<br>
                            {{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }}<br>
                            {{ $order->shippingAddress->province }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Update Status -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-edit"></i> Cập Nhật Trạng Thái
            </div>
            <div class="card-body">
                @if(!in_array($order->status, ['delivered', 'cancelled']))
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Trạng thái đơn hàng:</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Cập Nhật
                    </button>
                </form>
                @else
                <div class="alert alert-info mb-0">
                    Đơn hàng đã {{ $order->status === 'delivered' ? 'hoàn thành' : 'bị hủy' }}. Không thể thay đổi trạng thái.
                </div>
                @endif
            </div>
        </div>

        <!-- Update Payment Status -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-credit-card"></i> Trạng Thái Thanh Toán
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="payment_status" class="form-select" required>
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Cập Nhật
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Tóm Tắt
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> #{{ $order->id }}</p>
                <p><strong>Số sản phẩm:</strong> {{ $order->items->sum('quantity') }}</p>
                <p><strong>Tổng tiền:</strong> <span class="text-primary">{{ number_format($order->total_amount) }}đ</span></p>
                <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection