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
    <form method="post" action="{{ homeUrl('password/reset') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('label.email') }}" name="email" required value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
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
            <div class="col-xs-12">
                <button id="btn-register" type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('form.action_reset_password') }}</button>
            </div><!-- /.col -->
        </div>
    </form>
@endsection