@extends('home_themes.wow_skype.master.master')
@section('main_content')
    <div id="page-classrooms">
        <div class="classroom-heading clearfix">
            <div class="btn-group margin-top-20 pull-right">
                <button type="button" class="btn btn-default">{{ trans('label.status_closed') }}</button>
                <button type="button" class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ homeUrl('opening-classrooms') }}">{{ trans('label.status_opening') }}</a></li>
                </ul>
            </div>
            <h3 class="color-master"><strong>{{ trans_choice('label.classroom', 2) }}</strong></h3>
        </div>
        <div class="classroom-list big">
            @if($classrooms->count()>0)
                @foreach($classrooms as $classroom)
                    <hr>
                    <div id="classroom-{{ $classroom->id }}" class="classroom-item">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-4 margin-bottom-15">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="width-32 img-circle" src="{{ $classroom->teacherUserProfile->url_avatar_thumb }}">
                                            </div>
                                            <div class="media-body">
                                                <div class="color-master"><strong>{{ $classroom->teacherUserProfile->display_name }}</strong></div>
                                                <div>{{ trans_choice('label.teacher', 1) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 margin-bottom-15">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="width-32 img-circle" src="{{ $classroom->studentUserProfile->url_avatar_thumb }}">
                                            </div>
                                            <div class="media-body">
                                                <div class="color-master"><strong>{{ $classroom->studentUserProfile->display_name }}</strong></div>
                                                <div>{{ trans_choice('label.student', 1) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 margin-bottom-15">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="width-32 img-circle" src="{{ $classroom->supporter->url_avatar_thumb }}">
                                            </div>
                                            <div class="media-body">
                                                <div class="color-master"><strong>{{ $classroom->supporter->display_name }}</strong></div>
                                                <div>{{ trans_choice('label.supporter', 1) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 margin-bottom-15">
                                        <span>
                                            <strong>{{ trans('label.class_duration') }}:</strong>
                                            {{ $classroom->duration }} {{ trans_choice('label.hour_lc', $classroom->hours) }}
                                        </span>
                                        &nbsp; &nbsp; &nbsp;
                                        <span>
                                            <strong>{{ trans('label.class_spent_time') }}:</strong>
                                            {{ $classroom->spentTimeDuration }} {{ trans_choice('label.hour_lc', $classroom->spentTime) }}
                                        </span>
                                        @if(!empty($classroom->firstClassTime))
                                            &nbsp; &nbsp; &nbsp;
                                            <span>
                                                <strong>{{ trans('label.start_at') }}:</strong>
                                                {{ $classroom->firstClassTime->fullFormattedStartAtDate }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <a role="button" class="btn btn-primary btn-block" href="{{ homeUrl('classrooms/{id}', ['id' => $classroom->id]) }}">
                                    {{ trans('form.action_see_detail') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                <hr class="margin-bottom-none">
                {{ $pagination }}
            @else
                <hr>
                <div>
                    {{ trans('label.list_empty') }}
                </div>
            @endif
        </div>
    </div>
@endsection