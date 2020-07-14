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
    {!! extStyles() !!}
    @yield('extended_styles')
    {!! themeHeader() !!}
    <!--[if lt IE 9]>
    <script src="{{ _kExternalLink('html5shiv') }}"></script>
    <script src="{{ _kExternalLink('respond') }}"></script>
    <![endif]-->
</head>
<body>
@yield('body')
{!! libScripts() !!}
@yield('lib_scripts')
{!! extScripts() !!}
@yield('extended_scripts')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>