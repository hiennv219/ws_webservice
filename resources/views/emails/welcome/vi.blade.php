<!DOCTYPE html>
<html>
<head lang="{{ $site_locale }}">
    <meta charset="utf-8">
</head>
<body>
<h2>Bạn đã đăng ký tài khoản thành công</h2>
<p>
    Cảm ơn {{ $display_name }} đã tham gia <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Tài khoản của bạn:<br>
    - Hộp thư điện tử: {{ $email }}<br>
    - Tên tài khoản: {{ $name }}<br>
    - Mật khẩu: {{ $password }}<br>
    Hãy dùng thông tin trên để đăng nhập vào hệ thống của chúng tôi, sau khi bạn đã hoàn thành kích hoạt tài khoản tại địa chỉ:<br>
    <a href="{{ $url_activate }}">{{ $url_activate }}</a><br>
    <br>
    <br>
    Trân trọng,<br>
    {{ $site_name }} Team
</p>
</body>
</html>