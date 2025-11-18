@extends('frontend.layouts.app')

@section('title', 'Địa chỉ giao hàng')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Địa chỉ giao hàng</li>
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
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-edit"></i> Thông tin tài khoản
                    </a>
                    <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action active">
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

        <!-- Addresses List -->
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Địa Chỉ Của Tôi</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus"></i> Thêm địa chỉ
                    </button>
                </div>
                <div class="card-body">
                    @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h6 class="mb-0">{{ $address->full_name }}</h6>
                                            @if($address->is_default)
                                                <span class="badge bg-success">Mặc định</span>
                                            @endif
                                        </div>
                                        <p class="mb-1"><i class="fas fa-phone"></i> {{ $address->phone }}</p>
                                        <p class="mb-3 text-muted">
                                            <i class="fas fa-map-marker-alt"></i> {{ $address->fullAddress() }}
                                        </p>
                                        <div class="d-flex gap-2">
                                            @if(!$address->is_default)
                                            <form action="{{ route('account.addresses.set-default', $address) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-check"></i> Đặt mặc định
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('account.addresses.delete', $address) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-5x text-muted mb-3"></i>
                            <h5>Chưa có địa chỉ nào</h5>
                            <p class="text-muted">Thêm địa chỉ giao hàng để đặt hàng nhanh hơn</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus"></i> Thêm địa chỉ đầu tiên
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Thêm Địa Chỉ Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('account.addresses.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <input type="text" name="province" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                            <input type="text" name="district" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                            <input type="text" name="ward" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <input type="text" name="address_detail" class="form-control" placeholder="Số nhà, tên đường..." required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" class="form-check-input" id="is_default" value="1">
                        <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection