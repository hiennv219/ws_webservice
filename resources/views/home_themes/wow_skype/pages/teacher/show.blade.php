@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function() {
            $('a.teacher-video').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                helpers: {
                    overlay: {
                        locked: false
                    },
                    media : {}
                }
            });
            $('.toggle-tooltip').tooltip();
            $('.select2').select2({
                theme: 'bootstrap'
            });
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="change-timezone-modal" tabindex="false" role="dialog"
         aria-labelledby="change-timezone-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="change-timezone-modal-title">{{ trans('form.action_change') }} {{ trans('label.timezone') }}</h4>
                </div>
                <form method="post" action="{{ addErrorUrl(addRdrUrl(meUrl('timezone'))) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div id="change-timezone-modal-content" class="modal-body">
                        <div class="form-group">
                            <label for="inputTimeZone">{{ trans('label.timezone') }}:</label>
                            <select id="inputTimeZone" class="form-control select2" name="timezone" style="width: 100%;">
                                {!!  timeZoneListAsOptions(settings()->timezone) !!}
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('main_content')
    <div id="page-teacher">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3">
                        <img class="width-120 thumbnail padding-none border-master border-2x margin-bottom-15"
                             src="{{ $teacher->userProfile->url_avatar_thumb }}"
                             alt="{{ $teacher->userProfile->display_name }}">
                    </div>
                    <div class="col-md-9">
                        <h4 class="margin-top-none margin-bottom-5 color-master uppercase">
                            <strong>{{ $teacher->userProfile->display_name }}</strong>
                        </h4>
                        <div class="color-slave">
                            {{ allCountry($teacher->userProfile->nationality, 'name') }}
                        </div>
                        <div class="master-slave-bar margin-bottom-10 margin-top-5 width-150 clearfix">
                            <div class="bar pull-left"></div>
                            <div class="bar pull-right"></div>
                        </div>
                        @if($has_rates)
                            <div class="color-star biggest bold-600">
                                <span>{{ toFormattedNumber($average_rate) }}</span>
                            </div>
                            <div class="color-star">
                                <?php $star_split = intval($average_rate) != $average_rate; ?>
                                @if($star_split)
                                    @for($i = 1; $i <= intval($average_rate); ++$i)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                        <i class="fa fa-star-half-o"></i>
                                    @for($i = intval($average_rate) + 2; $i <= $max_rate; ++$i)
                                        <i class="fa fa-star-o"></i>
                                    @endfor
                                @else
                                    @for($i = 1; $i <= $max_rate; ++$i)
                                        <i class="fa {{ $i <= $average_rate ? 'fa-star' : 'fa-star-o' }}"></i>
                                    @endfor
                                @endif
                            </div>
                        @endif
                        <p>
                            @if(!empty($teacher->video_teaching_url))
                                <a target="_blank" role="button" class="btn btn-primary teacher-video margin-top-10" href="{{ $teacher->video_teaching_url }}">
                                    <i class="fa fa-play-circle"></i> {{ trans('label.teaching_video') }}
                                </a>
                                &nbsp;
                            @endif
                            @if(!empty($teacher->video_introduce_url))
                                <a target="_blank" role="button" class="btn btn-primary teacher-video margin-top-10" href="{{ $teacher->video_introduce_url }}">
                                    <i class="fa fa-play-circle"></i> {{ trans('label.self_introduction_video') }}
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
                <hr class="border-master">
                <div class="row">
                    <div class="col-md-3">
                        <label class="color-master">{{ trans('label.about_me') }}</label>
                    </div>
                    <div class="col-md-9">
                        <p>
                            {{ trans('label.gender_' . $teacher->userProfile->gender) }},
                            {{ $teacher->userProfile->age }} {{ trans_choice('label.year_old', $teacher->userProfile->age) }}.
                        </p>
                        <p>
                            {{ trans('label.living_in') }}
                            {{ $teacher->userProfile->city }}, {{ allCountry($teacher->userProfile->settings->country, 'name') }}.
                        </p>
                    </div>
                </div>
                <hr class="margin-top-10 border-master">
                <div class="row">
                    <div class="col-md-3">
                        <label class="color-master">{{ trans('label.self_introduction') }}</label>
                    </div>
                    <div class="col-md-9">
                        {!! $teacher->html_about_me !!}
                    </div>
                </div>
                <hr class="margin-top-10 border-master">
                <div class="row">
                    <div class="col-md-3">
                        <label class="color-master">{{ trans_choice('label.topic', 2) }}</label>
                    </div>
                    <div class="col-md-9">
                        @foreach($teacher->topics as $topic)
                            <span class="sausage-item sausage-item-primary"><strong>{{ $topic->name }}</strong></span>
                        @endforeach
                    </div>
                </div>
                <hr class="margin-top-10 border-master">
                <div class="row">
                    <div class="col-md-3">
                        <label class="color-master">{{ trans('label.teaching_experience') }}</label>
                    </div>
                    <div class="col-md-9">
                        {!! $teacher->html_experience !!}
                    </div>
                </div>
                <hr class="margin-top-10 border-master">
                <div class="row">
                    <div class="col-md-3">
                        <label class="color-master">{{ trans('label.teaching_methodology') }}</label>
                    </div>
                    <div class="col-md-9">
                        {!! $teacher->html_methodology !!}
                    </div>
                </div>
                @if($teacher->userProfile->educations->count() > 0)
                    <hr class="margin-top-10 border-master">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="color-master">{{ trans('label.education_history') }}</label>
                        </div>
                        <div class="col-md-9">
                            <div class="media-list">
                                @foreach($teacher->userProfile->educations as $education)
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="big color-master"><strong>{{ $education->field }}</strong></div>
                                            <div class="color-slave bold-700">
                                                <span class="color-lighter">{{ trans('label.at_lc') }}</span> {{ $education->school }}
                                                {!! $education->renderDuration('<span class="color-lighter">' . trans('label.from_lc') . '</span>', '<span class="color-lighter">' . trans('label.to_lc') . '</span>') !!}
                                            </div>
                                            @if(!empty($education->description))
                                                <div class="margin-top-5">{{ $education->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if($teacher->userProfile->works->count() > 0)
                    <hr class="border-master">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="color-master">{{ trans('label.work_history') }}</label>
                        </div>
                        <div class="col-md-9">
                            <div class="media-list">
                                @foreach($teacher->userProfile->works as $work)
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="big color-master"><strong>{{ $work->position }}</strong></div>
                                            <div class="color-slave bold-700">
                                                <span class="color-lighter">{{ trans('label.at_lc') }}</span> {{ $work->company }}
                                                {!! $work->renderDuration('<span class="color-lighter">' . trans('label.from_lc') . '</span>', '<span class="color-lighter">' . trans('label.to_lc') . '</span>') !!}
                                            </div>
                                            @if(!empty($work->description))
                                                <div class="margin-top-5">{{ $work->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <hr class="border-master">
                @if($has_rates)
                    <h5 class="bold-700 color-master uppercase margin-bottom-20">{{ trans('label.student_rating') }}</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="box-120 biggest box-circle bg-master box-center align-center color-white bold-700">
                                <span class="margin-top--10">{{ toFormattedNumber($average_rate) }}</span>
                            </div>
                            <p class="margin-top-10 text-center color-slave bold-700 big">
                                {{ trans('label.rate_by') }}
                                <span class="no-wrap">{{ $count_rating_students }} {{ trans_choice('label.student_lc', $count_rating_students) }}</span>
                            </p>
                        </div>
                        <div class="col-md-9">
                            <div class="row padding-v-15">
                                @foreach($rates_for_teacher as $name => $rate)
                                    <div class="col-sm-6">
                                        <h5 class="margin-v-none bold-600 color-master">{{ trans('label.rating') }} {{ trans('label.teacher_' . $name . '_rate') }}</h5>
                                        <div class="media margin-v-10">
                                            <div class="media-left">
                                                <span class="color-star font-24 bold-600">{{ toFormattedNumber($rate) }}</span>
                                            </div>
                                            <div class="media-body">
                                                <div class="color-star">
                                                    <?php $star_split = intval($rate) != $rate; ?>
                                                    @if($star_split)
                                                        @for($i = 1; $i <= intval($rate); ++$i)
                                                            <i class="fa fa-star"></i>
                                                        @endfor
                                                        <i class="fa fa-star-half-o"></i>
                                                        @for($i = intval($rate) + 2; $i <= $max_rate; ++$i)
                                                            <i class="fa fa-star-o"></i>
                                                        @endfor
                                                    @else
                                                        @for($i = 1; $i <= $max_rate; ++$i)
                                                            <i class="fa {{ $i <= $rate ? 'fa-star' : 'fa-star-o' }}"></i>
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="panel panel-default bg-lighter2">
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-left">
                                <i class="fa fa-user font-20 width-20"></i>
                            </div>
                            <div class="media-body text-middle">
                                @if($teacher->teaching_status == \Katniss\Everdeen\Models\Teacher::TEACHING_STATUS_AVAILABLE)
                                    <strong class="color-slave">{{ trans('label.status_teaching_available') }}</strong><br>
                                @elseif($teacher->teaching_status == \Katniss\Everdeen\Models\Teacher::TEACHING_STATUS_FULL_SCHEDULE)
                                    <strong class="text-danger">{{ trans('label.status_full_schedule') }}</strong><br>
                                @endif
                            </div>
                        </div>
                        @if(!empty($available_times['times']))
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-calendar font-20 width-20"></i>
                                </div>
                                    <div class="media-body text-middle">
                                        <strong class="color-master">{{ trans('label.available_times') }}</strong>
                                    </div>
                                    <div class="margin-v-10 padding-h-10 padding-v-5 br-4 bg-master color-white">
                                        @foreach($available_times['times'] as $available_time)
                                            <p class="margin-v-5">
                                                <strong>{{ trans('datetime.day_' . $available_time) }}</strong>
                                                @if(!empty($available_times['range_from'][$available_time]))
                                                    {{ trans('label.from_lc') }} {{ $available_times['range_from'][$available_time] }}
                                                @endif
                                                @if(!empty($available_times['range_to'][$available_time]))
                                                    {{ trans('label.to_lc') }} {{ $available_times['range_to'][$available_time] }}
                                                @endif
                                            </p>
                                        @endforeach
                                    </div>
                                    <div>
                                        {{ trans('label.your_timezone_is') }}
                                        <a class="text-underline hover-none toggle-tooltip" title="{{ trans('form.action_change') }}" data-toggle="modal" data-target="#change-timezone-modal" href="#">{{ settings()->timezone }}</a>
                                    </div>
                            </div>
                        @endif
                        @if(!$is_auth)
                            <a role="button" class="btn btn-success btn-block uppercase bold-700 margin-top-10"
                               href="{{ homeUrl('student/sign-up') }}?teacher_id={{ $teacher->user_id }}">
                                {{ trans('form.action_register_class') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="panel panel-default bg-lighter2">
                    <div class="panel-body">
                        <h4 class="margin-top-none margin-bottom-15">{{ trans('label.need_help') }}</h4>
                        @if(!empty($hot_line))
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-phone font-20 width-20"></i>
                                </div>
                                <div class="media-body text-middle">
                                    <a>{{ $hot_line }}</a>
                                </div>
                            </div>
                        @endif
                        @if(!empty($email))
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-send font-20 width-20"></i>
                                </div>
                                <div class="media-body text-middle">
                                    <a href="mail:{{ $email }}">{{ $email }}</a>
                                </div>
                            </div>
                        @endif
                        @if(!empty($skype_id))
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-skype font-20 width-20"></i>
                                </div>
                                <div class="media-body text-middle">
                                    <a href="skype:{{ $skype_id }}?chat">{{ $skype_id }} {{ !empty($skype_name) ? '(' . $skype_name . ')' : '' }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if($is_auth && $auth_user->hasRole(['admin', 'manager']))
                    {{ $payment_info_view }}
                @endif
            </div>
        </div>
    </div>
@endsection