@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','register')
@section('box_message', trans('label.account_activate_not'))
@section('auth_form')
    <form method="post" action="{{ homeUrl('password/email') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-2">
            </div><!-- /.col -->
            <div class="col-xs-8">
                @if($resend)
                    <div class="text-center alert alert-success">
                        {{ trans('label.account_activate_mail_resent') }}
                    </div>
                @else
                    <button id="btn-resend" type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ trans('label.account_activate_mail_resend') }}
                    </button>
                @endif
            </div><!-- /.col -->
            <div class="col-xs-2">
            </div><!-- /.col -->
        </div>

    </form>
@endsection