@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('auth_form')
    <h2 class="headline text-yellow text-center">404</h2>
    <div class="text-center margin-bottom">
        <h3><i class="fa fa-warning text-yellow"></i> {{ trans('error_http.404') }}.</h3>
        <p>{!! trans('error_http.404_admin', ['url' => homeUrl(), 'page' => trans('pages.home_title')]) !!}</p>
    </div>
@endsection