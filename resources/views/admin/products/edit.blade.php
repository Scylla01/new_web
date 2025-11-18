@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Sản Phẩm')
@section('page-title', 'Chỉnh Sửa Sản Phẩm')

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Thông Tin Cơ Bản
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (URL thân thiện)</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả Sản Phẩm</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" min="0" required>
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá So Sánh (Giá gốc)</label>
                            <div class="input-group">
                                <input type="number" name="compare_price" class="form-control @error('compare_price') is-invalid @enderror" value="{{ old('compare_price', $product->compare_price) }}" min="0">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('compare_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU (Mã sản phẩm) <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Lượng Tồn Kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
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
                        <label class="form-label">Hình Ảnh Chính</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" style="max-width: 200px; border-radius: 5px;">
                        </div>
                        <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*" onchange="previewMainImage(event)">
                        @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Để trống nếu không muốn thay đổi</small>
                        <div class="mt-2">
                            <img id="mainPreview" src="" alt="" style="max-width: 200px; display: none; border-radius: 5px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình Ảnh Phụ Hiện Tại</label>
                        <div class="row g-2">
                            @foreach($product->images as $image)
                            <div class="col-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                    <form action="{{ route('admin.products.delete-image', [$product, $image]) }}" method="POST" class="position-absolute top-0 end-0 m-1" onsubmit="return confirm('Xóa ảnh này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thêm Hình Ảnh Phụ</label>
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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hiển thị sản phẩm</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Thông Tin
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $product->id }}</p>
                    <p><strong>Lượt xem:</strong> {{ $product->view_count }}</p>
                    <p><strong>Tạo lúc:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Cập nhật:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
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
                    <i class="fas fa-save"></i> Cập Nhật Sản Phẩm
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