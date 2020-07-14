@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','register')
@section('box_message', trans('pages.account_register_desc'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="post" action="{{ homeUrl('auth/register/social') }}">
        {{ csrf_field() }}
        <input type="hidden" name="provider" value="{{ old('provider') }}">
        <input type="hidden" name="provider_id" value="{{ old('provider_id') }}">
        <input type="hidden" name="url_avatar" value="{{ old('url_avatar') }}">
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="{{ trans('label.full_name') }}" name="display_name" required value="{{ old('display_name') }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('label.email') }}" name="email" required value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="{{ trans('label.user_name') }}" name="name" value="{{ old('name') }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('label.password') }}" required name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('label.password_retype') }}" required name="password_confirmation">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button id="btn-register" type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('form.action_register') }}</button>
            </div><!-- /.col -->
        </div>
    </form>

    <a href="{{ homeUrl('auth/login') }}" class="text-center">{{ trans('label.already_member') }}</a><br>
    <a href="{{ homeUrl() }}" class="text-center">{{ trans('label.back_to_homepage') }}</a>
@endsection