@extends('home_themes.wow_skype.master.simple')
@section('main_content')
    <div id="page-sign-up">
        <h2>{{ trans('form.action_sign_up') }}</h2>
        <div class="row">
            <div class="col-md-5">
                <h3>
                    <strong>{{ trans_choice('label.student', 1) }}</strong>
                </h3>
                <hr>
                <p>{{ trans('label.help_sign_up_student') }}</p>
                <a href="{{ homeUrl('student/sign-up') }}" class="btn btn-primary btn-block clearfix">
                    <span class="pull-left uppercase">
                        <strong>{{ trans_choice('label.student', 1) }} {{ trans('form.action_sign_up') }}</strong>
                    </span>
                    <i class="fa fa-arrow-right pull-right"></i>
                </a>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
                <h3>
                    <strong>{{ trans_choice('label.teacher', 1) }}</strong>
                </h3>
                <hr>
                <p>{{ trans('label.help_sign_up_teacher') }}</p>
                <a href="{{ homeUrl('teacher/sign-up') }}" class="btn btn-primary btn-block clearfix">
                    <span class="pull-left uppercase">
                        <strong>{{ trans_choice('label.teacher', 1) }} {{ trans('form.action_sign_up') }}</strong>
                    </span>
                    <i class="fa fa-arrow-right pull-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection