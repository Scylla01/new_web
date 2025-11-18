@extends('admin.layouts.app')

@section('title', 'Thêm Mã Giảm Giá')
@section('page-title', 'Thêm Mã Giảm Giá Mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-ticket-alt"></i> Thông Tin Mã Giảm Giá
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="SUMMER2024" required style="text-transform: uppercase;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mã sẽ tự động viết HOA</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại Giảm Giá <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" id="couponType" required>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Giảm tiền cố định</option>
                                <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Giảm theo phần trăm</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá Trị <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}" min="0" step="0.01" required>
                            <span class="input-group-text" id="valueUnit">đ</span>
                        </div>
                        @error('value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="valueHint">VD: 50000 (giảm 50,000đ)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Đơn Hàng Tối Thiểu</label>
                            <div class="input-group">
                                <input type="number" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount') }}" min="0">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('min_order_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để trống = không giới hạn</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giảm Tối Đa</label>
                            <div class="input-group">
                                <input type="number" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount') }}" min="0">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('max_discount_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Chỉ áp dụng cho giảm %</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giới Hạn Số Lần Sử Dụng</label>
                        <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" value="{{ old('usage_limit') }}" min="1">
                        @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Để trống = không giới hạn</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kích hoạt mã giảm giá</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu Mã Giảm Giá
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
                <h6>Loại giảm giá:</h6>
                <ul class="small">
                    <li><strong>Giảm tiền:</strong> Giảm số tiền cố định (VD: giảm 50,000đ)</li>
                    <li><strong>Giảm %:</strong> Giảm theo phần trăm (VD: giảm 10%)</li>
                </ul>

                <h6 class="mt-3">Ví dụ:</h6>
                <div class="alert alert-info small">
                    <strong>SUMMER50</strong><br>
                    Giảm 50,000đ cho đơn từ 200,000đ
                </div>

                <div class="alert alert-warning small">
                    <strong>FLASH20</strong><br>
                    Giảm 20%, tối đa 100,000đ
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('couponType').addEventListener('change', function() {
        const valueUnit = document.getElementById('valueUnit');
        const valueHint = document.getElementById('valueHint');
        
        if (this.value === 'percent') {
            valueUnit.textContent = '%';
            valueHint.textContent = 'VD: 20 (giảm 20%)';
        } else {
            valueUnit.textContent = 'đ';
            valueHint.textContent = 'VD: 50000 (giảm 50,000đ)';
        }
    });
</script>
@endpush