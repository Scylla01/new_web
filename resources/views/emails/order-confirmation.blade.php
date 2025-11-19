<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đơn Hàng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .order-info h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        
        .products-table th {
            background-color: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .products-table tr:last-child td {
            border-bottom: none;
        }
        
        .total-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #667eea;
            padding-top: 15px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }
        
        .shipping-info {
            background-color: #e8f4f8;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }
        
        .shipping-info h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .shipping-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border-radius: 8px;
            text-align: center;
        }
        
        .contact-info p {
            margin: 5px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Đơn Hàng Đã Được Xác Nhận</h1>
            <p>Cảm ơn bạn đã mua hàng tại Bách Hóa Shop!</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p>Xin chào <strong>{{ $order->user->name }}</strong>,</p>
            <p style="margin-top: 15px;">
                Chúng tôi đã nhận được đơn hàng của bạn và đang xử lý. 
                Dưới đây là thông tin chi tiết đơn hàng:
            </p>
            
            <!-- Order Info -->
            <div class="order-info">
                <h2>Thông Tin Đơn Hàng</h2>
                <div class="info-row">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span class="info-value"><strong>{{ $order->order_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $order->status }}">
                            @switch($order->status)
                                @case('pending') Chờ xử lý @break
                                @case('confirmed') Đã xác nhận @break
                                @case('shipping') Đang giao @break
                                @case('delivered') Đã giao @break
                                @case('cancelled') Đã hủy @break
                            @endswitch
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thanh toán:</span>
                    <span class="info-value">
                        {{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' }}
                    </span>
                </div>
            </div>
            
            <!-- Products Table -->
            <h2 style="margin: 25px 0 15px; color: #333;">Sản Phẩm Đã Đặt</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center;">Số lượng</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                        <td style="text-align: right;"><strong>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="total-row" style="color: #28a745;">
                    <span>Giảm giá:</span>
                    <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span>TỔNG CỘNG:</span>
                    <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="shipping-info">
                <h3>📍 Địa Chỉ Giao Hàng</h3>
                <p><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                <p>{{ $order->shippingAddress->phone }}</p>
                <p>{{ $order->shippingAddress->address_line }}</p>
                <p>{{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }}</p>
                <p>{{ $order->shippingAddress->city }}</p>
                @if($order->note)
                <p style="margin-top: 10px;"><em>Ghi chú: {{ $order->note }}</em></p>
                @endif
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info">
                <p><strong>💬 Cần hỗ trợ?</strong></p>
                <p>Liên hệ: support@bachhoashop.com | Hotline: 1900-xxxx</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Bách Hóa Shop</strong></p>
            <p>Cảm ơn bạn đã tin tưởng và mua sắm tại cửa hàng của chúng tôi!</p>
            <p style="font-size: 12px; margin-top: 10px; color: #999;">
                Email này được gửi tự động, vui lòng không trả lời email này.
            </p>
        </div>
    </div>
</body>
</html>