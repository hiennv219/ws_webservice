<!DOCTYPE html>
<html>
<head lang="{{ $site_locale }}">
    <meta charset="utf-8">
</head>
<body>
<h2>Your registration has been successfully applied</h2>
<p>
    Thank {{ $display_name }} for registering at <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Your account:<br>
    - Email address: {{ $email }}<br>
    - User name: {{ $name }}<br>
    - Password: {{ $password }}<br>
    Use the information above to log in our site when you have already activate your account at:<br>
    <a href="{{ $url_activate }}">{{ $url_activate }}</a><br>
    <br>
    <br>
    Best regards,<br>
    From {{ $site_name }} Team
</p>
</body>
</html>