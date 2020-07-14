<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Hope you'll enjoy our website</h2>
<p>
    Thank {{ $display_name }} for registering at <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Click on the link below to activate your account:<br>
    <a href="{{ $url_activate }}">{{ $url_activate }}</a><br>
    After activating your account, you can start using our services with your current {{ $provider }} account.<br>
    Or lately, if you want to log in the website, use the information provided below:<br>
    - Email address: {{ $email }}<br>
    - User name: {{ $name }}<br>
    - Password: {{ $password }}<br>
    <br>
    <br>
    Best regards,<br>
    From {{ $site_name }} Team
</p>
</body>
</html>
