<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Chào mừng bạn đến với chúng tôi</h2>
<p>
    Cảm ơn {{ $display_name }} đã tham gia cộng đồng <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Địa chỉ để bạn kích hoạt tài khoản của mình:<br>
    <a href="{{ $url_activate }}">{{ $url_activate }}</a><br>
    Sau khi kích hoạt tài khoản, bạn có thể sử dụng tài khoản {{ $provider }} của bạn để truy cập các dịch vụ của chúng tôi.<br>
    Hoặc bạn có thể đăng nhập với thông tin người dùng tương ứng mà hệ thống tạo ra như bên dưới:<br>
    - Hộp thư điện tử: {{ $email }}<br>
    - Tên tài khoản: {{ $name }}<br>
    - Mật khẩu: {{ $password }}<br>
    <br>
    <br>
    Trân trọng,<br>
    {{ $site_name }} Team
</p>
</body>
</html>
