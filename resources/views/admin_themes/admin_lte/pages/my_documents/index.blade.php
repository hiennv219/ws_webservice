@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.my_documents_title'))
@section('page_description', trans('pages.my_documents_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('my-documents') }}">{{ trans('pages.my_documents_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('jquery-ui-theme-smoothness') }}">
    <link rel="stylesheet" href="{{ libraryAsset('elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('elfinder/css/theme.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('elfinder/js/elfinder.min.js') }}"></script>
    <script src="{{ libraryAsset('elfinder/js/i18n/elfinder.'.$site_locale.'.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('#elfinder').elfinder({
                lang: '{{ $site_locale }}',
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url : '{{ meUrl('documents/connector') }}',
                uiOptions : {
                    toolbar: [
                        ['back', 'forward'],
                        ['home', 'reload', 'up'],
                        ['mkdir', 'mkfile', 'upload'],
                        ['open', 'download', 'getfile'],
                        ['info', 'quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename', 'edit', 'resize', 'pixlr'],
                        ['search'],
                        ['view', 'sort']
                    ]
                },
                dateFormat : '{{ $dateFormat }} {{ $timeFormat }}'
            });
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div id="elfinder"></div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection