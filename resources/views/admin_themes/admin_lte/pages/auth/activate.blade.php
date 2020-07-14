@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','register')
@if($active)
    @section('box_message')
        {!! trans('error._success_account_activate', ['url'=> $url]) !!}
    @endsection
@else
    @section('box_message', trans('error.fail_account_activate'))
@endif