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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('admin_themes.admin_lte.master.admin_menu')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('page_title')
                <small>@yield('page_description')</small>
            </h1>
            @yield('page_breadcrumb')
        </section>
        <section class="content">
            @yield('page_content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            {{ $site_description }}
        </div>
        <strong>{{ trans('label.copyright') }} &copy; {{ date('Y') }} <a href="{{ $site_home_url }}">{{ $site_name }} {{ $site_version }}</a></strong>
    </footer>
    @include('admin_themes.admin_lte.master.admin_control_sidebar')
</div>
{!! libScripts() !!}
@yield('lib_scripts')
{!! extScripts() !!}
@yield('extended_scripts')
@include('admin_themes.admin_lte.master.common_modals')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>