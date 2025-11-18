@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tổng Doanh Thu</h6>
                        <h3 class="mb-0">{{ number_format($totalRevenue) }}đ</h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> Tháng này: {{ number_format($revenueThisMonth) }}đ
                        </small>
                    </div>
                    <i class="fas fa-dollar-sign stat-icon text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Đơn Hàng</h6>
                        <h3 class="mb-0">{{ $totalOrders }}</h3>
                        <small class="text-primary">
                            <i class="fas fa-clock"></i> Hôm nay: {{ $ordersToday }}
                        </small>
                    </div>
                    <i class="fas fa-shopping-cart stat-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Sản Phẩm</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i> Sắp hết: {{ $lowStockProducts }}
                        </small>
                    </div>
                    <i class="fas fa-box stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Khách Hàng</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                        <small class="text-danger">
                            <i class="fas fa-hourglass-half"></i> Chờ xử lý: {{ $pendingOrders }}
                        </small>
                    </div>
                    <i class="fas fa-users stat-icon text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i> Doanh Thu 30 Ngày Gần Đây
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-fire"></i> Sản Phẩm Bán Chạy
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($topProducts as $product)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ Str::limit($product->name, 30) }}</strong><br>
                            <small class="text-muted">{{ number_format($product->price) }}đ</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $product->total_sold ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list"></i> Đơn Hàng Gần Đây</span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Xem Tất Cả</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Sản Phẩm</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Đặt</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->items->count() }} sản phẩm</td>
                        <td><strong>{{ number_format($order->total_amount) }}đ</strong></td>
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
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenueChart);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => {
                const date = new Date(item.date);
                return date.getDate() + '/' + (date.getMonth() + 1);
            }),
            datasets: [{
                label: 'Doanh Thu (VNĐ)',
                data: revenueData.map(item => item.revenue),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush