@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Danh Mục')
@section('page-title', 'Chỉnh Sửa Danh Mục')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit"></i> Thông Tin Danh Mục
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (URL thân thiện)</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Danh Mục Cha</label>
                        <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">-- Không có (Danh mục gốc) --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                        @if($category->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="max-width: 200px; border-radius: 5px;">
                            </div>
                        @endif
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
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $category->sort_order) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hiển thị danh mục</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập Nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Thông Tin
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $category->id }}</p>
                <p><strong>Tạo lúc:</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Số sản phẩm:</strong> {{ $category->products()->count() }}</p>
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