@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Mã Giảm Giá')
@section('page-title', 'Chỉnh Sửa Mã Giảm Giá')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit"></i> Cập Nhật Mã Giảm Giá
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại Giảm Giá <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" id="couponType" required>
                                <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Giảm tiền cố định</option>
                                <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Giảm theo phần trăm</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá Trị <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $coupon->value) }}" min="0" step="0.01" required>
                            <span class="input-group-text" id="valueUnit">{{ $coupon->type === 'percent' ? '%' : 'đ' }}</span>
                        </div>
                        @error('value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $coupon->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Đơn Hàng Tối Thiểu</label>
                            <div class="input-group">
                                <input type="number" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('min_order_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giảm Tối Đa</label>
                            <div class="input-group">
                                <input type="number" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" min="0">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('max_discount_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giới Hạn Số Lần Sử Dụng</label>
                        <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                        @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $coupon->start_date->format('Y-m-d\TH:i')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $coupon->end_date->format('Y-m-d\TH:i')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kích hoạt mã giảm giá</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
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
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Thống Kê
            </div>
            <div class="card-body">
                <p><strong>Đã sử dụng:</strong> {{ $coupon->used_count }} lần</p>
                <p><strong>Giới hạn:</strong> {{ $coupon->usage_limit ?? 'Không giới hạn' }}</p>
                <p><strong>Tạo lúc:</strong> {{ $coupon->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật:</strong> {{ $coupon->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Lưu Ý
            </div>
            <div class="card-body">
                <ul class="small mb-0">
                    <li>Không nên thay đổi mã code nếu đã có người sử dụng</li>
                    <li>Thay đổi giá trị sẽ ảnh hưởng đến đơn hàng mới</li>
                    <li>Nếu đã hết hạn, hãy tạo mã mới thay vì gia hạn</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('couponType').addEventListener('change', function() {
        const valueUnit = document.getElementById('valueUnit');
        valueUnit.textContent = this.value === 'percent' ? '%' : 'đ';
    });
</script>
@endpush