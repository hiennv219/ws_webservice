<!DOCTYPE html>
<html>
<head lang="{{ $site_locale }}">
    <meta charset="utf-8">
</head>
<body>
<h2>Xin chào, {{ $display_name }}</h2>
<p>
    Mật khẩu của bạn đã được thiết lập lại tại <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Tài khoản hiện nay của bạn:<br>
    - Hộp thư điện tử: {{ $email }}<br>
    - Tên tài khoản: {{ $name }}<br>
    - Mật khẩu: {{ $password }}<br>
    Hãy dùng thông tin trên để đăng nhập vào những lần sau.<br>
    <br>
    <br>
    Trân trọng,<br>
    {{ $site_name }} Team
</p>
</body>
</html>