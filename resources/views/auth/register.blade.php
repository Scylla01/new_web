<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Bách Hóa Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 0;
        }
        .auth-card {
            max-width: 500px;
            width: 100%;
            padding: 0 20px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .card-header h3 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card-body {
            padding: 40px 30px;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .card-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-plus"></i> Đăng Ký</h3>
                <p class="mb-0">Tạo tài khoản mới</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        <strong>Lỗi:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user me-1"></i> Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Nguyễn Văn A"
                               required 
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope me-1"></i> Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="example@email.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-phone me-1"></i> Số Điện Thoại</label>
                        <input type="text" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" 
                               placeholder="0123456789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock me-1"></i> Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Tối thiểu 6 ký tự"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock me-1"></i> Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-control" 
                               placeholder="Nhập lại mật khẩu"
                               required>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Bằng việc đăng ký, bạn đồng ý với điều khoản sử dụng của chúng tôi.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3">
                        <i class="fas fa-user-plus me-2"></i> Đăng Ký
                    </button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Đã có tài khoản? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>