@extends('frontend.layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-credit-card"></i> Thanh Toán</h2>

    @if(session('cart') && count(session('cart')) > 0)
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Shipping Info -->
            <div class="col-md-8">
                <!-- Customer Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Thông Tin Khách Hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', auth()->user()->name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Địa Chỉ Giao Hàng</h5>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->addresses->count() > 0)
                        <!-- Saved Addresses -->
                        <div class="mb-3">
                            <label class="form-label">Chọn địa chỉ có sẵn:</label>
                            @foreach(auth()->user()->addresses as $address)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="address_id" id="address{{ $address->id }}" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                <label class="form-check-label" for="address{{ $address->id }}">
                                    <strong>{{ $address->full_name }}</strong> - {{ $address->phone }}<br>
                                    <small>{{ $address->fullAddress() }}</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <p class="mb-2"><strong>Hoặc nhập địa chỉ mới:</strong></p>
                        @endif

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province') }}" required>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <input type="text" name="district" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') }}" required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <input type="text" name="ward" class="form-control @error('ward') is-invalid @enderror" value="{{ old('ward') }}" required>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <input type="text" name="address_detail" class="form-control @error('address_detail') is-invalid @enderror" value="{{ old('address_detail') }}" placeholder="Số nhà, tên đường..." required>
                            @error('address_detail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Phương Thức Thanh Toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                <strong>Thanh toán khi nhận hàng (COD)</strong><br>
                                <small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">
                                <strong>Chuyển khoản ngân hàng</strong><br>
                                <small class="text-muted">Chuyển khoản trực tiếp vào tài khoản</small>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                            <label class="form-check-label" for="vnpay">
                                <strong>VNPay</strong><br>
                                <small class="text-muted">Thanh toán qua cổng VNPay</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo">
                            <label class="form-check-label" for="momo">
                                <strong>MoMo</strong><br>
                                <small class="text-muted">Thanh toán qua ví MoMo</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Ghi Chú</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú thêm (không bắt buộc)">{{ old('note') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Đơn Hàng</h5>
                    </div>
                    <div class="card-body">
                        <!-- Products -->
                        <div class="mb-3">
                            @php $total = 0; @endphp
                            @foreach(session('cart') as $id => $item)
                            @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong>{{ $item['name'] }}</strong><br>
                                    <small class="text-muted">SL: {{ $item['quantity'] }} x {{ number_format($item['price']) }}đ</small>
                                </div>
                                <div>
                                    <strong>{{ number_format($subtotal) }}đ</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr>

                        <!-- Summary -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <strong>{{ number_format($total) }}đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <strong>30,000đ</strong>
                        </div>
                        @if(session('discount'))
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Giảm giá:</span>
                            <strong>-{{ number_format(session('discount')) }}đ</strong>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Tổng cộng:</strong>
                            <h4 class="text-primary mb-0">{{ number_format($total + 30000 - (session('discount') ?? 0)) }}đ</h4>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check"></i> Đặt Hàng
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lock"></i> Thanh toán an toàn & bảo mật
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h3>Giỏ hàng trống</h3>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Mua sắm ngay</a>
    </div>
    @endif
</div>
@endsection