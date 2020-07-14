@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_styles')
    <style>
        .select2-container--bootstrap .select2-dropdown{
            min-width: 320px;
            border-top: 1px solid #66afe9;
        }
    </style>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            });

            $('#inputGender').on('change', function () {
                var queryString = window.location.search.substr(1);
                if (queryString.length == 0) {
                    window.location.href = '?gender=' + $(this).val();
                    return;
                }
                queryString = queryString.split('&');
                var pageIndex = -1;
                var found = false;
                for (var i in queryString) {
                    if (queryString[i].indexOf('gender=') == 0) {
                        queryString[i] = 'gender=' + $(this).val();
                        found = true;
                    }
                    if (queryString[i].indexOf('page=') == 0) {
                        pageIndex = i;
                    }
                }
                if (pageIndex != -1) {
                    queryString.splice(pageIndex, 1);
                }
                if (!found) {
                    queryString.push('gender=' + $(this).val());
                }
                window.location.href = '?' + queryString.join('&');
            });

            $('#inputNationality').on('change', function () {
                var queryString = window.location.search.substr(1);
                if (queryString.length == 0) {
                    window.location.href = '?nationality=' + $(this).val();
                    return;
                }
                queryString = queryString.split('&');
                var pageIndex = -1;
                var found = false;
                for (var i in queryString) {
                    if (queryString[i].indexOf('nationality=') == 0) {
                        queryString[i] = 'nationality=' + $(this).val();
                        found = true;
                    }
                    if (queryString[i].indexOf('page=') == 0) {
                        pageIndex = i;
                    }
                }
                if (pageIndex != -1) {
                    queryString.splice(pageIndex, 1);
                }
                if (!found) {
                    queryString.push('nationality=' + $(this).val());
                }
                window.location.href = '?' + queryString.join('&');
            });
        });
    </script>
@endsection
@section('main_content')
    <div id="page-teachers">
        <div class="row">
            <div class="col-md-4 hidden-sm hidden-xs">
                <div id="teachers-sidebar" class="bg-lighter2 padding-15">
                    @include('home_themes.wow_skype.pages.teacher.index_help')
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel box-shadow-none border-none">
                    <div class="panel-body padding-none">
                        <form>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="inputTopics" class="control-label sr-only">{{ trans_choice('label.topic', 2) }}</label>
                                        <select class="form-control select2" id="inputTopics" name="topics[]" multiple="multiple"
                                                data-placeholder="{{ trans('label.choose_topics_help') }} ({{ $topics->count() == 0 ? 'IELTS, TOEFL' : $topics->chunk(3)->first()->implode('name', ', ') }}, ...)" style="width: 100%;">
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->id }}"{{ in_array($topic->id, $search_topics) ? ' selected' : '' }}>
                                                    {{ $topic->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-sm-pl-none">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success lighter btn-block uppercase bold-700">
                                            {{ trans('form.action_search') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="big" style="line-height: 32px">{{ trans('label.choose_teachers_help') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-sm-pl-none">
                                    <div class="form-group">
                                        <label for="inputNationality" class="control-label sr-only">{{ trans('label.nationality') }}</label>
                                        <select id="inputNationality" class="form-control select2" name="nationality" style="width: 100%;">
                                            <option value="">
                                                {{ trans('label.nationality') }}
                                            </option>
                                            {!! countriesAsOptions($search_nationality) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-sm-pl-none">
                                    <div class="form-group">
                                        <label for="inputGender" class="control-label sr-only">{{ trans('label.gender') }}</label>
                                        <select id="inputGender" class="form-control" name="gender">
                                            <option value="">
                                                {{ trans('label.gender') }}
                                            </option>
                                            @foreach(allGenders() as $gender)
                                                <option value="{{ $gender }}"{{ $gender == $search_gender ? ' selected' : '' }}>
                                                    {{ trans('label.gender_'.$gender) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="teacher-list">
                            @if($teachers->count()>0)
                                @foreach($teachers as $teacher)
                                    <div class="row margin-top-15">
                                        <div class="col-sm-3">
                                            <a target="_blank" href="{{ homeUrl('teachers/{id}', ['id' => $teacher->user_id]) }}">
                                                <img class="thumbnail border-master border-2x img-responsive"
                                                     src="{{ $teacher->userProfile->url_avatar_thumb }}"
                                                     alt="{{ $teacher->userProfile->display_name }}">
                                            </a>
                                        </div>
                                        <div class="col-sm-6 col-sm-pl-none col-sm-pr-none">
                                            <h5 class="margin-bottom-5 margin-top-none">
                                                <a target="_blank" class="uppercase"
                                                   href="{{ homeUrl('teachers/{id}', ['id' => $teacher->user_id]) }}">
                                                    <strong>{{ $teacher->userProfile->display_name }}</strong>
                                                </a>
                                            </h5>
                                            <div class="color-slave">
                                                {{ allCountry($teacher->userProfile->nationality, 'name') }}
                                            </div>
                                            <div class="master-slave-bar margin-bottom-10 margin-top-5 width-150 clearfix">
                                                <div class="bar pull-left"></div>
                                                <div class="bar pull-right"></div>
                                            </div>
                                            <p class="big">
                                                {{ shorten($teacher->about_me, \Katniss\Everdeen\Utils\AppConfig::SMALLER_SHORTEN_TEXT_LENGTH) }}
                                                <a target="_blank" href="{{ homeUrl('teachers/{id}', ['id' => $teacher->user_id]) }}">&raquo;</a>
                                            </p>
                                            <p class="help-block">
                                                <em class="color-normal bold-700">
                                                    @if($teacher->topics->count() > 0)
                                                        #{{ $teacher->topics->implode('name', ' #') }}
                                                    @endif
                                                </em>
                                            </p>
                                        </div>
                                        <div class="col-sm-3 text-center">
                                            <div class="color-star biggest bold-600">
                                                {{ toFormattedNumber($average_rate_for_teachers[$teacher->user_id]) }}
                                            </div>
                                            <div class="color-star margin-bottom-5">
                                                <?php $star_split = intval($average_rate_for_teachers[$teacher->user_id]) != $average_rate_for_teachers[$teacher->user_id]; ?>
                                                @if($star_split)
                                                    @for($i = 1; $i <= intval($average_rate_for_teachers[$teacher->user_id]); ++$i)
                                                        <i class="fa fa-star"></i>
                                                    @endfor
                                                    <i class="fa fa-star-half-o"></i>
                                                    @for($i = intval($average_rate_for_teachers[$teacher->user_id]) + 2; $i <= $max_rate; ++$i)
                                                        <i class="fa fa-star-o"></i>
                                                    @endfor
                                                @else
                                                    @for($i = 1; $i <= $max_rate; ++$i)
                                                        <i class="fa {{ $i <= $average_rate_for_teachers[$teacher->user_id] ? 'fa-star' : 'fa-star-o' }}"></i>
                                                    @endfor
                                                @endif
                                            </div>
                                            <p class="text-center">
                                                {{ trans('label.rate_by') }}
                                                <strong class="no-wrap">
                                                    {{ $count_rating_students_for_teachers[$teacher->user_id] }}
                                                    {{ trans_choice('label.student_lc', $count_rating_students_for_teachers[$teacher->user_id]) }}
                                                </strong>
                                            </p>
                                            <p>
                                                <a target="_blank" class="btn btn-primary"
                                                   href="{{ homeUrl('teachers/{id}', ['id' => $teacher->user_id]) }}">
                                                    {{ trans('form.action_view_profile') }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-center">
                                    {{ $pagination }}
                                </div>
                            @else
                                <div class="margin-top-15">
                                    {{ trans('label.list_empty') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection