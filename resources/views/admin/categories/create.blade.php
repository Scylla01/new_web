@extends('admin.layouts.app')

@section('title', 'Thêm Danh Mục')
@section('page-title', 'Thêm Danh Mục Mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus"></i> Thông Tin Danh Mục
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (URL thân thiện)</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Để trống để tự động tạo">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Ví dụ: thuc-pham-tuoi-song</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Danh Mục Cha</label>
                        <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">-- Không có (Danh mục gốc) --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình Ảnh</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <img id="preview" src="" alt="" style="max-width: 200px; display: none; border-radius: 5px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thứ Tự Sắp Xếp</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hiển thị danh mục</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu Danh Mục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Hướng Dẫn
            </div>
            <div class="card-body">
                <h6>Lưu ý khi tạo danh mục:</h6>
                <ul class="small">
                    <li>Tên danh mục không được để trống</li>
                    <li>Slug sẽ tự động tạo từ tên nếu để trống</li>
                    <li>Danh mục con phải thuộc 1 danh mục cha</li>
                    <li>Hình ảnh nên có kích thước 500x500px</li>
                    <li>Thứ tự càng nhỏ càng hiển thị trước</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush