<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Bạn đã xác nhận quên mật khẩu tài khoản của mình tại <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a></h2>
<p>
    Bấm vào đây để tiến hành thay đổi mật khẩu:<br>
    <a href="{{ homeUrl('password/reset/{token}', ['token' => $token], $site_locale) }}">
        {{ homeUrl('password/reset/{token}', ['token' => $token], $site_locale) }}
    </a>
</p>
<p>Nếu không phải bạn, xin vui lòng không bấm vào đường dẫn phía trên.</p>
<br>
<br>
<p>Trân trọng,<br>
{{ $site_name }} Team</p>
</body>
</html>