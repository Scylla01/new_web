@extends('admin.layouts.app')

@section('title', 'Thêm Banner Mới')
@section('page-title', 'Thêm Banner Mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Thông Tin Banner</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mô tả ngắn hiển thị trên banner</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*" onchange="previewImage(event)" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kích thước khuyến nghị: 1920x600px. Định dạng: JPG, PNG, GIF (Max: 2MB)</small>
                        
                        <!-- Image Preview -->
                        <div class="mt-3" id="imagePreview" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Link (URL)</label>
                            <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" 
                                   value="{{ old('link') }}" placeholder="https://example.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">URL khi click vào banner</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Text nút CTA</label>
                            <input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" 
                                   value="{{ old('button_text') }}" placeholder="Xem ngay">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Số thứ tự càng nhỏ hiển thị càng trước</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Hiển thị banner</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu Banner
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
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <h6>Kích thước ảnh khuyến nghị:</h6>
                <ul>
                    <li>Desktop: 1920x600px</li>
                    <li>Tablet: 1024x400px</li>
                    <li>Mobile: 768x400px</li>
                </ul>
                
                <h6 class="mt-3">Lưu ý:</h6>
                <ul>
                    <li>Sử dụng ảnh có độ phân giải cao</li>
                    <li>Nội dung chính nên ở giữa ảnh</li>
                    <li>Tránh text quá nhỏ</li>
                    <li>Dung lượng file không quá 2MB</li>
                </ul>
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