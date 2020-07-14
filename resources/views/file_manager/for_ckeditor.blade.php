@extends('simple')
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
        function urlParam(name) {
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results == null) {
                return null;
            }
            else {
                return decodeURIComponent(results[1]) || 0;
            }
        }

        $(function () {
            var funcNum = urlParam('CKEditorFuncNum');
            var elfinderOptions = {
                lang: '{{ $site_locale }}',
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ meUrl('documents/connector') }}{{ empty($custom_type) ? '' : '?custom_type='.$custom_type }}',
                getFileCallback: function (file) {
                    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                    window.close();
                },
                uiOptions: {
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
                dateFormat: '{{ $dateFormat }} {{ $timeFormat }}'
            };
            var onlyMimes = '{{ $onlyMimes }}';
            if (onlyMimes.trim() != '') {
                elfinderOptions.onlyMimes = onlyMimes.split(',');
            }
            $('#elfinder').elfinder(elfinderOptions);
        });
    </script>
@endsection
@section('body')
    <div id="elfinder"></div>
@endsection