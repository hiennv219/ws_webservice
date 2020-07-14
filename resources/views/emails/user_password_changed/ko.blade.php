<!DOCTYPE html>
<html>
<head lang="{{ $site_locale }}">
    <meta charset="utf-8">
</head>
<body>
<h2>Hi, {{ $display_name }}</h2>
<p>
    Your password has been reset at <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Your current account:<br>
    - Email address: {{ $email }}<br>
    - User name: {{ $name }}<br>
    - Password: {{ $password }}<br>
    Use the information above to log in our site next times.<br>
    <br>
    <br>
    Best regards,<br>
    From {{ $site_name }} Team
</p>
</body>
</html>