@extends('home_themes.wow_skype.master.master_footer_placed')
@section('main_content')
    <div id="{{ $html_page_id }}" class="page-profile">
        <div class="row">
            <div class="col-md-4">
                @if($auth_user->hasRole('teacher') && !$auth_user->teacherProfile->isApproved)
                    <div class="alert alert-danger">{{ trans('label.status_teacher_not_approved') }}</div>
                @endif
                @if($auth_user->hasRole('student') && !$auth_user->studentProfile->isApproved)
                    <div class="alert alert-danger">{{ trans('label.status_student_not_approved') }}</div>
                @endif
                {{ $profile_menu }}
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{{ $panel_heading }}</strong>
                    </div>
                    <div class="panel-body">
                        @yield('profile_content')
                    </div>
                </div>
                @yield('profile_content_extra')
            </div>
        </div>
    </div>
@endsection