@extends('admin.layouts.app')

@section('title', 'Quản lý Banner')
@section('page-title', 'Quản lý Banner')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-images"></i> Danh Sách Banner</h5>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Banner Mới
        </a>
    </div>
    <div class="card-body">
        @if($banners->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="80">Hình ảnh</th>
                            <th>Tiêu đề</th>
                            <th width="120">Thứ tự</th>
                            <th width="120" class="text-center">Trạng thái</th>
                            <th width="200" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr>
                            <td>
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                                     class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $banner->title }}</strong>
                                @if($banner->description)
                                    <br><small class="text-muted">{{ Str::limit($banner->description, 60) }}</small>
                                @endif
                                @if($banner->link)
                                    <br><small><i class="fas fa-link"></i> <a href="{{ $banner->link }}" target="_blank">{{ Str::limit($banner->link, 40) }}</a></small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $banner->sort_order }}</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.banners.toggle-status', $banner) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        <i class="fas fa-{{ $banner->is_active ? 'check' : 'times' }}"></i>
                                        {{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $banners->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có banner nào. Hãy thêm banner đầu tiên!</p>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Banner
                </a>
            </div>
        @endif
    </div>
</div>
@endsection