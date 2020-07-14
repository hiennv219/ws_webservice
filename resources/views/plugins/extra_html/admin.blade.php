@extends('plugins.default_widget.admin')
@section('lib_scripts')
    <script src="{{ libraryAsset('ckeditor-4.5.5/ckeditor.js') }}"></script>
    <script src="{{ libraryAsset('ckeditor-4.5.5/adapters/jquery.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.ck-editor').ckeditor({
                language: '{{ $site_locale }}',
                filebrowserBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}',
                filebrowserFlashBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=flash',
                filebrowserFlashUploadUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=flash',
                filebrowserImageBrowseLinkUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=images',
                filebrowserImageBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=images',
                customConfig: '{{ libraryAsset('ckeditor-4.5.5/config_typical.js') }}'
            });
        });
    </script>
@endsection