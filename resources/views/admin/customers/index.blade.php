@extends('admin.layouts.app')

@section('title', 'Quản Lý Khách Hàng')
@section('page-title', 'Quản Lý Khách Hàng')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-users"></i> Danh Sách Khách Hàng
    </div>
    <div class="card-body">
        <!-- Search -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên, email, số điện thoại..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm Kiếm
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ Tên</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Tổng Đơn</th>
                        <th>Tổng Chi Tiêu</th>
                        <th>Ngày Đăng Ký</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>
                            <strong>{{ $customer->name }}</strong>
                        </td>
                        <td>
                            {{ $customer->email }}
                            @if($customer->email_verified_at)
                                <i class="fas fa-check-circle text-success" title="Email đã xác thực"></i>
                            @endif
                        </td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $customer->orders_count }}</span>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($customer->total_spent ?? 0) }}đ</strong>
                        </td>
                        <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có khách hàng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection