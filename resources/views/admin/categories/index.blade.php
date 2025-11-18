@extends('admin.layouts.app')

@section('title', 'Quản Lý Danh Mục')
@section('page-title', 'Quản Lý Danh Mục')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list"></i> Danh Sách Danh Mục</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Danh Mục
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="categoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình Ảnh</th>
                        <th>Tên Danh Mục</th>
                        <th>Slug</th>
                        <th>Danh Mục Cha</th>
                        <th>Số Sản Phẩm</th>
                        <th>Trạng Thái</th>
                        <th>Thứ Tự</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                            @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                    <i class="fas fa-image text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            @if($category->parent)
                                <span class="badge bg-secondary">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $category->products_count }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }}"></i>
                                    {{ $category->is_active ? 'Hoạt động' : 'Ẩn' }}
                                </button>
                            </form>
                        </td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#categoriesTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "language": {
                "search": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "emptyTable": "Chưa có danh mục nào"
            }
        });
    });
</script>
@endpush