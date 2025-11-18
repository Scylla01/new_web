@extends('admin.layouts.app')

@section('title', 'Thêm Sản Phẩm')
@section('page-title', 'Thêm Sản Phẩm Mới')

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Thông Tin Cơ Bản
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
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
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả Sản Phẩm</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0" required>
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá So Sánh (Giá gốc)</label>
                            <div class="input-group">
                                <input type="number" name="compare_price" class="form-control @error('compare_price') is-invalid @enderror" value="{{ old('compare_price') }}" min="0">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('compare_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để hiển thị % giảm giá</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU (Mã sản phẩm) <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Lượng Tồn Kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}" min="0" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-images"></i> Hình Ảnh Sản Phẩm
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Hình Ảnh Chính <span class="text-danger">*</span></label>
                        <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*" onchange="previewMainImage(event)" required>
                        @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <img id="mainPreview" src="" alt="" style="max-width: 200px; display: none; border-radius: 5px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình Ảnh Phụ (Tối đa 5 ảnh)</label>
                        <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple onchange="previewImages(event)">
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="imagesPreview" class="mt-2 row g-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-folder"></i> Phân Loại
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Danh Mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hiển thị sản phẩm</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Hướng Dẫn
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Tên sản phẩm ngắn gọn, dễ hiểu</li>
                        <li>SKU phải là duy nhất</li>
                        <li>Hình ảnh nên có tỷ lệ 1:1 (vuông)</li>
                        <li>Giá so sánh phải lớn hơn giá bán</li>
                        <li>Mô tả chi tiết giúp bán hàng tốt hơn</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Sản Phẩm
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function previewMainImage(event) {
        const preview = document.getElementById('mainPreview');
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

    function previewImages(event) {
        const container = document.getElementById('imagesPreview');
        container.innerHTML = '';
        const files = event.target.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">`;
                container.appendChild(col);
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush