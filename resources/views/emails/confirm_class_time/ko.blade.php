<!DOCTYPE html>
<html>
<head lang="{{ $site_locale }}">
    <meta charset="utf-8">
</head>
<body>
<h2>New class time has been added</h2>
<div>
    Thank {{ $display_name }} for joining the classroom at <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Your teacher has added a new class time.
</div>
<div>
    - Subject: {{ $subject }}<br>
    - Duration: {{ $duration }}<br>
    - Start at: {{ $start_at }}<br>
    - Content:<br>
    <blockquote>{!! $html_content !!}</blockquote>
</div>
<div>
    If you agree with your teacher about the information of the class time above, please confirm it by clicking on this link:<br>
    <a href="{{ $url_confirmation }}">{{ $url_confirmation }}</a><br>
    Your teacher will be only paid when the class time is confirmed.<br><br>
    For more details, please check at the link below and let us know if you have any issue with this new class time:<br>
    <a href="{{ $url_classroom }}">{{ $url_classroom }}</a><br>
    <br>
    <br>
    Best regards,<br>
    From {{ $site_name }} Team
</div>
</body>
</html>