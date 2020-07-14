@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_error_title', ['code' => 401]))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li>{{ trans('pages.admin_error_title', ['code' => 401]) }}</li>
    </ol>
@endsection
@section('page_content')
    <div class="error-page">
        <h2 class="headline text-yellow">401</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> {{ trans('error_http.401') }}.</h3>
            <p>{!! trans('error_http.401_admin', ['url' => adminUrl(), 'page' => trans('pages.admin_dashboard_title')]) !!}</p>
        </div>
    </div>
@endsection