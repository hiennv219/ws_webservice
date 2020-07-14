@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('box_message', trans('pages.account_password_reset_desc'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="post" action="{{ homeUrl('password/email') }}">
        {{ csrf_field() }}
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('label.email') }}" required name="email" value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('label.account_password_reset_send_mail') }}</button>
            </div><!-- /.col -->
        </div>
    </form>

    <div class="social-auth-links text-center">
    </div>

    <a href="{{ homeUrl('auth/login') }}" class="text-center">{{ trans('label.remember_account') }}</a><br>
    @if($app_settings->register_enable)
        <a href="{{ homeUrl('auth/register') }}" class="text-center">{{ trans('label.register_membership') }}</a><br>
    @endif
    <a href="{{ homeUrl() }}" class="text-center">{{ trans('label.back_to_homepage') }}</a>
@endsection