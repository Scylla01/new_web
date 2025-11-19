@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Banner')
@section('page-title', 'Chỉnh sửa Banner')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Chỉnh Sửa Banner</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $banner->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="mb-2">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                                 class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
                        </div>
                        
                        <label class="form-label">Thay đổi hình ảnh (Tùy chọn)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                        
                        <!-- New Image Preview -->
                        <div class="mt-3" id="imagePreview" style="display: none;">
                            <strong>Ảnh mới:</strong>
                            <img id="preview" src="" alt="Preview" class="img-thumbnail d-block mt-2" style="max-width: 100%; max-height: 200px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Link (URL)</label>
                            <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" 
                                   value="{{ old('link', $banner->link) }}" placeholder="https://example.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Text nút CTA</label>
                            <input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" 
                                   value="{{ old('button_text', $banner->button_text) }}" placeholder="Xem ngay">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" 
                                       {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Hiển thị banner</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập Nhật Banner
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Thông tin</h5>
            </div>
            <div class="card-body">
                <p><strong>Ngày tạo:</strong><br>{{ $banner->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật lần cuối:</strong><br>{{ $banner->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong><br>
                    <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $banner->is_active ? 'Đang hiển thị' : 'Đã ẩn' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection