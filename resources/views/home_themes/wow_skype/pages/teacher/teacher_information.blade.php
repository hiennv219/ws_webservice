@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-teacher-information',
    'panel_heading' => trans('label.teacher_information'),
])
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
            $('[data-toggle="popover"]').popover({html: true}).on('click', function (e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
@section('profile_content')
    <form method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @include('messages_after_action')
        <div class="form-group">
            <label for="inputVideoIntroduceUrl" class="control-label">{{ trans('label.video_introduce_url') }}</label>
            <input type="text" placeholder="Youtube URL" value="{{ $teacher->video_introduce_url }}"
                   class="form-control" id="inputVideoIntroduceUrl" name="video_introduce_url">
        </div>
        <div class="form-group">
            <label for="inputVideoTeachingUrl" class="control-label">{{ trans('label.video_teaching_url') }}</label>
            <input type="text" placeholder="Youtube URL" value="{{ $teacher->video_teaching_url }}"
                   class="form-control" id="inputVideoTeachingUrl" name="video_teaching_url">
        </div>
        <div class="form-group">
            <label for="inputTopics" class="control-label required">{{ trans_choice('label.topic', 2) }}</label>
            <select class="form-control select2" id="inputTopics" name="topics[]" required multiple="multiple"
                    data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.topic_lc', 2) }}" style="width: 100%;">
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}"{{ in_array($topic->id, $teacher_topic_ids) ? ' selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="inputAboutMe" class="control-label required">{{ trans('label.about_me') }}</label>
            <a class="help-sign" href="#" data-toggle="popover" data-placement="top" data-trigger="focus"
               data-content="{{ trans('label.teacher_about_me_eg') }}">
                <i class="fa fa-question-circle-o"></i>
            </a>
            <textarea rows="6" class="form-control" id="inputAboutMe" name="about_me" required minlength="200"
                      placeholder="{{ trans('label.about_me') }}">{{ $teacher->about_me }}</textarea>
        </div>
        <div class="form-group">
            <label for="inputTeachingExperience" class="control-label required">{{ trans('label.teaching_experience') }}</label>
            <a class="help-sign" href="#" data-toggle="popover" data-placement="top" data-trigger="focus"
               data-content="{{ trans('label.teacher_experience_eg') }}">
                <i class="fa fa-question-circle-o"></i>
            </a>
            <textarea rows="6" class="form-control" id="inputTeachingExperience" name="experience" required minlength="100"
                      placeholder="{{ trans('label.teaching_experience') }}">{{ $teacher->experience }}</textarea>
        </div>
        <div class="form-group">
            <label for="inputTeachingMethodology" class="control-label required">{{ trans('label.teaching_methodology') }}</label>
            <a class="help-sign" href="#" data-toggle="popover" data-placement="top" data-trigger="focus"
               data-content="{{ trans('label.teacher_methodology_eg') }}">
                <i class="fa fa-question-circle-o"></i>
            </a>
            <textarea rows="6" class="form-control" id="inputTeachingMethodology" name="methodology" required minlength="100"
                      placeholder="{{ trans('label.teaching_methodology') }}">{{ $teacher->methodology }}</textarea>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_save') }}</strong></button>
        </div>
    </form>
@endsection