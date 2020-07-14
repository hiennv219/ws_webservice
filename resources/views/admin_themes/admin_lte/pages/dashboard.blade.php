@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_dashboard_title'))
@section('page_description', trans('pages.admin_dashboard_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    </ol>
@endsection
@section('page_content')
    <div class="row">
        @if($auth_user->hasRole(['admin', 'manager']))
            <div class="col-sm-6 col-xs-12">
                <div class="info-box bg-aqua no-icon">
                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('label.teacher', 2) }}</span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('approved-teachers') }}">
                                <span>{{ toFormattedNumber($count_approved_teachers, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_approved_teachers_title') }}</small>
                            </a>
                        </span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('registering-teachers') }}">
                                <span>{{ toFormattedNumber($count_registering_teachers, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_registering_teachers_title') }}</small>
                            </a>
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $pc_teachers }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ trans('label.increase_30days', ['number' => $pc_teachers . '%']) }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-sm-6 col-xs-12">
                <div class="info-box bg-red no-icon">
                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('label.student', 2) }}</span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('approved-students') }}">
                                <span>{{ toFormattedNumber($count_approved_students, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_approved_students_title') }}</small>
                            </a>
                        </span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('registering-students') }}">
                                <span>{{ toFormattedNumber($count_registering_students, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_registering_students_title') }}</small>
                            </a>
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $pc_students }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ trans('label.increase_30days', ['number' => $pc_students . '%']) }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-sm-6 col-xs-12">
                <div class="info-box bg-green no-icon">
                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('label.classroom', 2) }}</span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('opening-classrooms') }}">
                                <span>{{ toFormattedNumber($count_opening_classrooms, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_opening_classrooms_title') }}</small>
                            </a>
                        </span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('closed-classrooms') }}">
                                <span>{{ toFormattedNumber($count_closed_classrooms, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_closed_classrooms_title') }}</small>
                            </a>
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $pc_classrooms }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ trans('label.increase_30days', ['number' => $pc_classrooms . '%']) }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-sm-6 col-xs-12">
                <!-- /.info-box -->
                <div class="info-box bg-yellow no-icon">
                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('label.learning_request', 2) }}</span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('register-learning-requests') }}">
                                <span>{{ toFormattedNumber($count_newly_lr, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_register_learning_requests_title') }}</small>
                            </a>
                        </span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('processed-learning-requests') }}">
                                <span>{{ toFormattedNumber($count_processed_lr, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_processed_learning_requests_title') }}</small>
                            </a>
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $pc_lr }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ trans('label.increase_30days', ['number' => $pc_lr . '%']) }}
                        </span>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        @endif
        @if($auth_user->hasRole(['admin', 'editor']))
            <div class="col-sm-6 col-xs-12">
                <!-- /.info-box -->
                <div class="info-box bg-purple no-icon">
                    <div class="info-box-content">
                        <span class="info-box-text">{{ trans_choice('label.article', 2) }}</span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('published-articles') }}">
                                <span>{{ toFormattedNumber($count_published_articles, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_published_articles_title') }}</small>
                            </a>
                        </span>
                        <span class="info-box-number">
                            <a href="{{ adminUrl('teacher-articles') }}">
                                <span>{{ toFormattedNumber($count_teacher_articles, 0) }}</span>
                                <small class="bold-400">{{ trans('pages.admin_teacher_articles_title') }} ({{ trans('label.status_not_published') }})</small>
                            </a>
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $pc_articles }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ trans('label.increase_30days', ['number' => $pc_articles . '%']) }}
                        </span>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        @endif
    </div>
    @if(!$auth_user->hasRole(['admin', 'manager', 'editor']))
        <p>We have nothing showing to you.</p>
    @endif
@endsection