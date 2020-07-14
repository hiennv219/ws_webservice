@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_ui_lang_php_title'))
@section('page_description', trans('pages.admin_ui_lang_php_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="#">{{ trans('pages.admin_ui_lang_title') }}</a></li>
        <li><a href="{{ adminUrl('ui-lang/php') }}">{{ trans('pages.admin_ui_lang_php_title') }}</a></li>
    </ol>
@endsection
@section('page_content')
    @include('admin_themes.admin_lte.pages.ui_lang.file', ['form_action' => adminUrl('ui-lang/php')])
@endsection