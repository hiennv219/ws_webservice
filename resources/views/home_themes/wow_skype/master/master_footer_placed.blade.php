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
<header>
    @include('home_themes.wow_skype.master.header', ['header_nav_simple' => true])
</header>
<main>
    @yield('before_main_content')
    <div class="wrapper">
        @yield('main_content')
    </div>
    @yield('after_main_content')
</main>
<footer>
    @yield('footer')
    @include('home_themes.wow_skype.master.footer_lower')
  
</footer>
{!! libScripts() !!}
@yield('lib_scripts')
{!! extScripts() !!}
@yield('extended_scripts')
@include('home_themes.wow_skype.master.common_modals')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>