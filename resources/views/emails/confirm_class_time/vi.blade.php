<!DOCTYPE html>
<html>
<head lang="{{ $site_locale }}">
    <meta charset="utf-8">
</head>
<body>
<h2>Buổi học mới đã được thêm</h2>
<div>
    Cảm ơn {{ $display_name }} đã tham gia lớp học tại <a href="{{ homeUrl(null, [], $site_locale) }}">{{ $site_name }}</a>.<br>
    Giáo viên của bạn vừa thêm một buổi học mới.
</div>
<div>
    - Chủ đề: {{ $subject }}<br>
    - Thời lượng: {{ $duration }}<br>
    - Bắt đầu lúc: {{ $start_at }}<br>
    - Nội dung:<br>
    <blockquote>{!! $html_content !!}</blockquote>
</div>
<div>
    Nếu bạn đồng ý với thông tin lớp học phía trên, hãy xác nhận bằng cách bấm vào đường dẫn bên dưới:<br>
    <a href="{{ $url_confirmation }}">{{ $url_confirmation }}</a><br>
    Giáo viên của bạn chỉ được trả công khi buổi học đã được xác nhận.<br><br>
    Hãy kiểm tra lại diễn biến lớp học theo đường dẫn sau và cho chúng tôi biết nếu bạn gặp bất cứ vấn đề gì với buổi học mới này:<br>
    <a href="{{ $url_classroom }}">{{ $url_classroom }}</a><br>
    <br>
    <br>
    Trân trọng,<br>
    {{ $site_name }} Team
</div>
</body>
</html>