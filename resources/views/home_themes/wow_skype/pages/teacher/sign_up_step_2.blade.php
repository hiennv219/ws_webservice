@extends('home_themes.wow_skype.master.simple')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('extended_styles')
    <style>
        .popover{max-width: 800px}
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            });
            $('#inputOtherCertificate').on('change', function () {
                if($(this).is(':checked')) {
                    $('#inputOtherCertificates').removeClass('hide').prop('required', true);
                }
                else {
                    $('#inputOtherCertificates').addClass('hide').prop('required', false);
                }
            });
            $('[data-toggle="popover"]').popover({html: true}).on('click', function (e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
@section('main_content')
    <div id="page-teacher-sign-up-step">
        <h2>{{ trans('label.become_our_teacher', [], 'en') }}</h2>
        <p>{{ trans('label.become_our_teacher_help', [], 'en') }}</p>

        <ul class="nav nav-wizard margin-top-20 margin-bottom-30">
            <li><a href="#"><span>1</span> <strong>{{ trans('label.personal_information', [], 'en') }}</strong></a></li>
            <li class="active"><a href="#"><span>2</span> <strong>{{ trans('label.teacher_information', [], 'en') }}</strong></a></li>
        </ul>

        <form method="post" action="{{ homeUrl('teacher/sign-up/step/{step}', ['step' => 2]) }}">
            {{ csrf_field() }}
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="inputTopics" class="control-label required">{{ trans_choice('label.topic', 2, [], 'en') }}</label>
                <select class="form-control select2" id="inputTopics" name="topics[]" required multiple="multiple"
                        data-placeholder="{{ trans('form.action_select', [], 'en') }} {{ trans_choice('label.topic_lc', 2, [], 'en') }}" style="width: 100%;">
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}"{{ in_array($topic->id, $teacher_topic_ids) ? ' selected' : '' }}>
                            {{ $topic->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="inputAboutMe" class="control-label required">{{ trans('label.about_me', [], 'en') }}</label>
                <a class="help-sign" href="#" data-toggle="popover" data-placement="top" data-trigger="focus"
                   data-content="{{ trans('label.teacher_about_me_eg', [], 'en') }}">
                    <i class="fa fa-question-circle-o"></i>
                </a>
                <textarea rows="10" class="form-control" id="inputAboutMe" name="about_me" required minlength="200"
                          placeholder="{{ trans('label.about_me', [], 'en') }}">{{ $teacher->about_me }}</textarea>
            </div>
            <div class="form-group">
                <label for="inputTeachingExperience" class="control-label required">{{ trans('label.teaching_experience', [], 'en') }}</label>
                <a class="help-sign" href="#" data-toggle="popover" data-placement="top" data-trigger="focus"
                   data-content="{{ trans('label.teacher_experience_eg', [], 'en') }}">
                    <i class="fa fa-question-circle-o"></i>
                </a>
                <textarea rows="10" class="form-control" id="inputTeachingExperience" name="experience" required minlength="100"
                          placeholder="{{ trans('label.teaching_experience', [], 'en') }}">{{ $teacher->experience }}</textarea>
            </div>
            <div class="form-group">
                <label for="inputTeachingMethodology" class="control-label required">{{ trans('label.teaching_methodology', [], 'en') }}</label>
                <a class="help-sign" href="#" data-toggle="popover" data-placement="top" data-trigger="focus"
                   data-content="{{ trans('label.teacher_methodology_eg', [], 'en') }}">
                    <i class="fa fa-question-circle-o"></i>
                </a>
                <textarea rows="10" class="form-control" id="inputTeachingMethodology" name="methodology" required minlength="100"
                          placeholder="{{ trans('label.teaching_methodology', [], 'en') }}">{{ $teacher->methodology }}</textarea>
            </div>
            <div class="form-group">
                <label class="control-label">{{ trans('label.have_any_english_certificates', [], 'en') }}</label>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="row">
                            @foreach($certificates as $certificate)
                                <div class="col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input {{ array_key_exists(strtolower($certificate), $teacher_certificates) ? 'checked' : '' }}
                                                   {!! strtolower($certificate) == 'others' ? 'id="inputOtherCertificate"' : '' !!}
                                                   type="checkbox" name="certificates[]"
                                                   value="{{ $certificate }}">
                                            {{ $certificate }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <label for="inputOtherCertificates" class="sr-only">Others</label>
                <input id="inputOtherCertificates" type="text" class="form-control{{ empty($teacher_other_certificates) ? ' hide' : '' }}" name="other_certificates" value="{{ $teacher_other_certificates }}">
            </div>
            <div class="form-group">
                <label for="inputVideoIntroduceUrl" class="control-label">{{ trans('label.video_introduce_url', [], 'en') }}</label>
                <input type="text" placeholder="Youtube URL" value="{{ $teacher->video_introduce_url }}"
                       class="form-control" id="inputVideoIntroduceUrl" name="video_introduce_url">
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_complete', [], 'en') }}</strong></button>
            </div>
        </form>
        <div class="margin-v-30">&nbsp;</div>
    </div>
@endsection