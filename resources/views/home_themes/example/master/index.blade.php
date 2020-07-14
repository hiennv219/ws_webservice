<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">{{ trans('label.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand page-scroll" href="{{ homeUrl() }}">{{ $site_name }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            {{ $main_menu }}
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
@yield('extra_sections')
<footer>
    <p><a href="{{ homeUrl() }}">{{ $site_name }}</a> &copy; {{ date('Y') }}. Based on <a href="https://laravel.com/" rel="nofollow">Laravel</a>.</p>
</footer>
{!! libScripts() !!}
@yield('lib_scripts')
{!! extScripts() !!}
@yield('extended_scripts')
@include('home_themes.example.master.common_modals')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>