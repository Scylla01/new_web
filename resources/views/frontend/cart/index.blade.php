@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-shopping-cart"></i> Giỏ Hàng Của Bạn</h2>

    @if(session('cart') && count(session('cart')) > 0)
    <div class="row">
        <!-- Cart Items -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $item)
                                @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item['image']) }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 width="80" 
                                                 class="me-3 rounded">
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="{{ route('products.show', $item['slug']) }}" class="text-decoration-none text-dark">
                                                        {{ $item['name'] }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">SKU: {{ $item['sku'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($item['price']) }}đ</strong>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <div class="input-group" style="width: 120px;">
                                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="btn btn-outline-secondary btn-sm" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity_display" class="form-control form-control-sm text-center" value="{{ $item['quantity'] }}" readonly>
                                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($subtotal) }}đ</strong>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cart Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i> Xóa giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($total) }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong>30,000đ</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Tổng cộng:</strong>
                        <h4 class="text-primary mb-0">{{ number_format($total + 30000) }}đ</h4>
                    </div>

                    <!-- Coupon Code -->
                    <div class="mb-3">
                        <label class="form-label">Mã giảm giá:</label>
                        <form action="{{ route('cart.apply-coupon') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã giảm giá">
                                <button type="submit" class="btn btn-outline-primary">Áp dụng</button>
                            </div>
                        </form>
                    </div>

                    @auth
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-credit-card"></i> Thanh toán
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập để thanh toán
                        </a>
                    @endauth

                    <!-- Payment Methods -->
                    <div class="mt-3 text-center">
                        <small class="text-muted">Phương thức thanh toán:</small>
                        <div class="mt-2">
                            <i class="fab fa-cc-visa fa-2x me-2"></i>
                            <i class="fab fa-cc-mastercard fa-2x me-2"></i>
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h3>Giỏ hàng trống</h3>
        <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-shopping-bag"></i> Mua sắm ngay
        </a>
    </div>
    @endif
</div>
@endsection