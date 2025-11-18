@extends('frontend.layouts.app')

@section('title', 'Thông tin tài khoản')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Thông tin tài khoản</li>
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
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                    </a>
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action active">
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

        <!-- Profile Form -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> Thông Tin Cá Nhân</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Đổi Mật Khẩu</h5>
                        <p class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</p>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu Thay Đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-bag fa-3x text-primary mb-2"></i>
                            <h3>{{ auth()->user()->orders->count() }}</h3>
                            <p class="text-muted mb-0">Đơn hàng</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-dollar-sign fa-3x text-success mb-2"></i>
                            <h3>{{ number_format(auth()->user()->orders->sum('total_amount')) }}đ</h3>
                            <p class="text-muted mb-0">Tổng chi tiêu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar fa-3x text-info mb-2"></i>
                            <h3>{{ auth()->user()->created_at->diffForHumans() }}</h3>
                            <p class="text-muted mb-0">Thành viên từ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection