<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang chủ') - Bách Hóa Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-green: #2d5f3f;
            --secondary-green: #3a7052;
            --light-green: #4a8763;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Top Header - FIXED */
        .top-header {
            background: var(--primary-green);
            color: white;
            padding: 15px 0;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Logo - FIXED */
        .logo {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            min-width: 140px;
        }

        .logo i {
            font-size: 32px;
        }

        .logo-text {
            line-height: 1.1;
        }

        .logo-text small {
            font-size: 11px;
            font-weight: 500;
        }

        /* Search Box - FIXED */
        .search-container {
            flex: 1;
            max-width: 750px;
        }

        .search-box {
            display: flex;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .search-box input {
            flex: 1;
            border: none;
            padding: 11px 15px;
            font-size: 14px;
            min-width: 0;
        }

        .search-box input:focus {
            outline: none;
        }

        .search-box select {
            border: none;
            border-left: 1px solid #e0e0e0;
            padding: 11px 12px;
            background: white;
            cursor: pointer;
            font-size: 14px;
            max-width: 180px;
        }

        .search-box select:focus {
            outline: none;
        }

        .search-box button {
            background: #92c947;
            border: none;
            padding: 0 25px;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .search-box button:hover {
            background: #7fb839;
        }

        /* User Actions - FIXED */
        .user-actions {
            display: flex;
            gap: 25px;
            align-items: center;
            color: white;
            margin-left: 20px;
        }

        .user-actions a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .user-actions a:hover {
            opacity: 0.85;
        }

        .user-info {
            text-align: left;
            display: flex;
            flex-direction: column;
        }

        .user-info small {
            font-size: 11px;
            opacity: 0.85;
            line-height: 1.2;
        }

        .user-info strong {
            font-size: 14px;
            font-weight: 600;
        }

        .user-actions .dropdown-toggle::after {
            margin-left: 5px;
        }

        .user-actions .dropdown-menu {
            background: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-top: 10px;
        }

        .user-actions .dropdown-item {
            padding: 10px 20px;
            color: #333;
        }

        .user-actions .dropdown-item:hover {
            background: #f8f9fa;
        }

        /* Cart Icon - FIXED */
        .cart-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cart-icon i {
            font-size: 28px;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }

        /* Bottom Navigation - FIXED */
        .bottom-nav {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-top: 1px solid #e8e8e8;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-menu li a {
            display: block;
            color: #333;
            text-decoration: none;
            padding: 16px 20px;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.3s;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            color: var(--primary-green);
        }

        .nav-menu li a i {
            font-size: 10px;
            margin-left: 3px;
        }

        .nav-menu .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 250px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            z-index: 1000;
            margin: 0;
            padding: 0;
            border: none;
            border-radius: 0 0 4px 4px;
        }

        .nav-menu .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu a {
            color: #333 !important;
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
            background: white !important;
            text-transform: none !important;
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        .dropdown-menu a:hover {
            background: #f8f9fa !important;
            color: var(--primary-green) !important;
            padding-left: 25px;
        }

        /* Product Card */
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: var(--primary-green);
        }

        .product-price-old {
            text-decoration: line-through;
            color: #999;
            font-size: 14px;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
            margin-top: 80px;
        }

        .footer h5 {
            color: white;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-green);
            border: none;
        }

        .btn-primary:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(45, 95, 63, 0.4);
        }

        /* ==================== CHAT WIDGET STYLES ==================== */
        
        /* Chat Button - Circular with green background */
        .chat-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--primary-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(45, 95, 63, 0.4);
            z-index: 9998;
            transition: all 0.3s;
        }

        .chat-button:hover {
            background: var(--secondary-green);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(45, 95, 63, 0.6);
        }

        .chat-button i {
            font-size: 28px;
            color: white;
        }

        .chat-button .chat-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            border: 2px solid white;
        }

        .chat-button .chat-badge.show {
            display: flex;
        }

        /* Chat Widget Container */
        .chat-widget {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 380px;
            height: 550px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            z-index: 9999;
            overflow: hidden;
        }

        .chat-widget.show {
            display: flex;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Chat Header */
        .chat-header {
            background: var(--primary-green);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-header-title .avatar {
            width: 40px;
            height: 40px;
            background: white;
            color: var(--primary-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .chat-header-title div h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .chat-header-title div small {
            font-size: 12px;
            opacity: 0.9;
        }

        .chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .chat-close:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Chat Init Form */
        .chat-init-form {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chat-init-form h5 {
            color: var(--primary-green);
            margin-bottom: 10px;
        }

        .chat-init-form p {
            color: #666;
            margin-bottom: 25px;
        }

        .chat-init-form .form-group {
            margin-bottom: 20px;
        }

        .chat-init-form label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .chat-init-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .chat-init-form input:focus {
            outline: none;
            border-color: var(--primary-green);
        }

        .chat-init-form button {
            width: 100%;
            padding: 12px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .chat-init-form button:hover {
            background: var(--secondary-green);
        }

        /* Chat Messages Area */
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
            min-height: 300px; 
            max-height: 400px;
        }

        .chat-message {
            margin-bottom: 15px;
            display: flex;
        }

        .chat-message.user {
            justify-content: flex-end;
        }

        .chat-message.admin {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
        }

        .chat-message.user .message-bubble {
            background: var(--primary-green);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-message.admin .message-bubble {
            background: white;
            border: 1px solid #e0e0e0;
            color: #333;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 4px;
        }

        /* Chat Input */
        .chat-input-area {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            background: white;
            flex-shrink: 0;
        }

        .chat-input-form {
            display: flex;
            gap: 10px;
        }

        .chat-input-form input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            font-size: 14px;
        }

        .chat-input-form input:focus {
            outline: none;
            border-color: var(--primary-green);
        }

        .chat-input-form button {
            width: 45px;
            height: 45px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .chat-input-form button:hover {
            background: var(--secondary-green);
            transform: scale(1.1);
        }

        /* Scrollbar Styling */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
        body::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        html {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        .sidebar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        .sidebar {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        /* Responsive Chat */
        @media (max-width: 768px) {
            .chat-widget {
                width: calc(100% - 20px);
                height: calc(100% - 120px);
                bottom: 10px;
                right: 10px;
                left: 10px;
            }

            .chat-button {
                bottom: 20px;
                right: 20px;
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .header-content {
                flex-wrap: wrap;
            }

            .search-container {
                order: 3;
                width: 100%;
                max-width: 100%;
                margin-top: 15px;
            }

            .user-actions {
                margin-left: auto;
            }
        }

        @media (max-width: 768px) {
            .top-header {
                padding: 10px 0;
            }

            .logo {
                font-size: 16px;
                min-width: 120px;
            }

            .logo i {
                font-size: 28px;
            }

            .search-box select {
                max-width: 130px;
                font-size: 13px;
                padding: 11px 8px;
            }

            .search-box button {
                padding: 0 20px;
            }

            .user-actions {
                gap: 15px;
            }

            .user-info small {
                font-size: 10px;
            }

            .user-info strong {
                font-size: 13px;
            }

            .cart-icon i {
                font-size: 24px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-menu li a {
                padding: 12px 15px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .header-content {
                gap: 10px;
            }

            .logo {
                min-width: 100px;
                font-size: 14px;
            }

            .logo i {
                font-size: 24px;
            }

            .search-box input {
                font-size: 13px;
                padding: 10px;
            }

            .search-box select {
                display: none;
            }

            .user-info small {
                display: none;
            }

            .nav-menu li a {
                padding: 10px 12px;
                font-size: 12px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    <i class="fas fa-store"></i>
                    <div class="logo-text">
                        BÁCH HÓA<br>
                        <small>SHOP</small>
                    </div>
                </a>

                <!-- Search -->
                <div class="search-container">
                    <form action="{{ route('products.index') }}" method="GET" class="search-box">
                        <input type="text" name="search" placeholder="Tìm nhanh sản phẩm..." value="{{ request('search') }}">
                        <select name="category" onchange="this.form.submit()">
                            <option value="">Tất cả danh mục</option>
                            @foreach(\App\Models\Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->get() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="user-info dropdown-toggle" data-bs-toggle="dropdown">
                                <small>Đăng nhập / Đăng ký</small>
                                <strong>{{ auth()->user()->name }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}">Đơn hàng của tôi</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.profile') }}">Thông tin tài khoản</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="user-info">
                            <small>Đăng nhập / Đăng ký</small>
                            <strong>Tài khoản</strong>
                        </a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge" id="cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="container">
            <ul class="nav-menu">
                <li class="dropdown">
                    <a href="{{ route('products.index') }}">
                        DANH MỤC SẢN PHẨM <i class="fas fa-chevron-down ms-1"></i>
                    </a>
                    <div class="dropdown-menu">
                        @foreach(\App\Models\Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->limit(10)->get() as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </li>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">GIỚI THIỆU</a></li>
                <li><a href="{{ route('products.index', ['featured' => 1]) }}">SẢN PHẨM NỔI BẬT</a></li>
                <li><a href="{{ route('products.index', ['sort' => 'newest']) }}">SẢN PHẨM MỚI</a></li>
                <li><a href="{{ route('products.index') }}">KHUYẾN MÃI</a></li>
                <li><a href="#">LIÊN HỆ</a></li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-store"></i> Bách Hóa Shop</h5>
                    <p>Cửa hàng bách hóa trực tuyến uy tín, chất lượng với hàng ngàn sản phẩm đa dạng.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5>Về chúng tôi</h5>
                    <ul class="footer-links">
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Tin tức</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Chính sách</h5>
                    <ul class="footer-links">
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Liên hệ</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
                        <li><i class="fas fa-phone"></i> 1900 1234</li>
                        <li><i class="fas fa-envelope"></i> support@bachhoashop.com</li>
                        <li><i class="fas fa-clock"></i> 8:00 - 22:00 (Hàng ngày)</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Bách Hóa Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Chat Widget -->
    <div class="chat-button" id="chatButton" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
        <span class="chat-badge" id="chatBadge">0</span>
    </div>

    <div class="chat-widget" id="chatWidget">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="chat-header-title">
                <div class="avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h6>Hỗ Trợ Khách Hàng</h6>
                    <small>Online - Sẵn sàng hỗ trợ</small>
                </div>
            </div>
            <button class="chat-close" onclick="toggleChat()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Chat Init Form (shown first time) -->
        <div class="chat-init-form" id="chatInitForm">
            <h5>Chào mừng bạn! 👋</h5>
            <p>Vui lòng nhập thông tin để bắt đầu trò chuyện với chúng tôi</p>
            <form onsubmit="initChat(event)">
                <div class="form-group">
                    <label>Tên của bạn *</label>
                    <input type="text" id="chatName" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="chatEmail" placeholder="email@example.com" required>
                </div>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i> Bắt đầu trò chuyện
                </button>
            </form>
        </div>

        <!-- Chat Content (shown after init) -->
        <div style="display: none; flex: 1; display: flex; flex-direction: column;" id="chatContent">
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be loaded here -->
            </div>

            <div class="chat-input-area">
                <form class="chat-input-form" onsubmit="sendMessage(event)">
                    <input type="text" id="messageInput" placeholder="Nhập tin nhắn..." required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Update cart count
        function updateCartCount() {
            // TODO: Implement cart count via AJAX
        }

        // Load cart count on page load
        $(document).ready(function() {
            updateCartCount();
            checkChatSession();
            startChatPolling();
        });

        // ==================== CHAT WIDGET FUNCTIONS ====================
        
        let chatInitialized = false;
        let chatPollingInterval = null;

        function toggleChat() {
            const widget = document.getElementById('chatWidget');
            widget.classList.toggle('show');
        }

        function checkChatSession() {
            // Check if user already has chat session
            fetch('/chat/messages')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        chatInitialized = true;
                        showChatContent();
                        displayMessages(data.messages);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function initChat(event) {
            event.preventDefault();
            
            const name = document.getElementById('chatName').value;
            const email = document.getElementById('chatEmail').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/chat/init', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ name: name, email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    chatInitialized = true;
                    showChatContent();
                    
                    // Send welcome message
                    const welcomeMsg = {
                        text: 'Xin chào! Cảm ơn bạn đã liên hệ với Bách Hóa Shop. Chúng tôi sẽ phản hồi bạn trong giây lát.',
                        sender: 'admin',
                        time: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
                    };
                    displayMessage(welcomeMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }

        function showChatContent() {
            document.getElementById('chatInitForm').style.display = 'none';
            document.getElementById('chatContent').style.display = 'flex';
        }

        function sendMessage(event) {
            event.preventDefault();
            
            if (!chatInitialized) return;
            
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    displayMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }

        function displayMessage(message) {
            const container = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message ' + message.sender;
            messageDiv.innerHTML = `
                <div class="message-bubble">
                    <div>${message.text}</div>
                    <div class="message-time">${message.time}</div>
                </div>
            `;
            container.appendChild(messageDiv);
            container.scrollTop = container.scrollHeight;
        }

        function displayMessages(messages) {
            const container = document.getElementById('chatMessages');
            container.innerHTML = '';
            messages.forEach(msg => {
                displayMessage(msg);
            });
        }

        function loadNewMessages() {
            if (!chatInitialized) return;

            fetch('/chat/messages')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayMessages(data.messages);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateChatBadge() {
            fetch('/chat/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('chatBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.add('show');
                    } else {
                        badge.classList.remove('show');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function startChatPolling() {
            // Poll for new messages every 3 seconds
            chatPollingInterval = setInterval(() => {
                loadNewMessages();
                updateChatBadge();
            }, 3000);
        }

        // Stop polling when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (chatPollingInterval) {
                clearInterval(chatPollingInterval);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>