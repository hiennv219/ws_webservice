<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @yield('site_meta')
    <title>{!! themeTitle() !!}</title>
    <meta name="description" content="{!! themeDescription() !!}">
    <meta name="keywords" content="{!! themeKeywords() !!}">
    <meta name="author" content="{!! themeAuthor() !!}">
    <meta name="application-name" content="{!! themeApplicationName() !!}">
    @include('fav_icons')
    {!! libStyles() !!}
    @yield('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
    {!! extStyles() !!}
    @yield('extended_styles')
    {!! themeHeader() !!}
    <!--[if lt IE 9]>
    <script src="{{ _kExternalLink('html5shiv') }}"></script>
    <script src="{{ _kExternalLink('respond') }}"></script>
    <![endif]-->
</head>
<body class="hold-transition @yield('auth_type')-page">
<div class="@yield('auth_type')-box">
    <div class="@yield('auth_type')-logo">
        <a href="{{ homeUrl() }}"><strong>{{ $site_name }}</strong></a>
    </div>
    <div class="@yield('auth_type')-box-body">
        <p class="@yield('auth_type')-box-msg">@yield('box_message')</p>
        @yield('auth_form')
    </div>
</div>
{!! libScripts() !!}
<script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@yield('lib_scripts')
<script>
    $(function () {
        $('[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
{!! extScripts() !!}
@yield('extended_scripts')
@include('admin_themes.admin_lte.master.common_modals')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>
