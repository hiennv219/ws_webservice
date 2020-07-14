@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('box_message', trans('pages.account_login_desc'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="post" action="{{ homeUrl('auth/login') }}">
        {{ csrf_field() }}
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="{{ trans('label.email') }} {{ trans('label.or_lc') }} {{ trans('label.user_name') }}" required name="account" value="{{ old('account') }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('label.password') }}" required name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label for="inputRemember">
                        <input id="inputRemember" type="checkbox" name="remember"{{ old('remember') ? ' checked' : '' }}>
                        &nbsp; {{ trans('label.remember_me') }}
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('form.action_login') }}</button>
            </div><!-- /.col -->
        </div>
    </form>
    @include('admin_themes.admin_lte.pages.auth.social_auth_links')
    <a href="{{ homeUrl('password/email') }}" class="text-center">{{ trans('label.forgot_password') }}</a><br>
    @if($app_settings->register_enable)
        <a href="{{ homeUrl('auth/register') }}" class="text-center">{{ trans('label.register_membership') }}</a><br>
    @endif
    <a href="{{ homeUrl() }}" class="text-center">{{ trans('label.back_to_homepage') }}</a>
@endsection