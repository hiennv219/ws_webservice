@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-educations-and-works',
    'panel_heading' => trans('label.user_professional_skill'),
])
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('extended_styles')
    <style>
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.date-picker').datepicker({
                format: '{{ $date_js_format }}',
                language: '{{ $site_locale }}',
                enableOnReadonly: false
            });
            $('.select2').select2({
                theme: 'bootstrap'
            });
            $('.work-more').on('click', function (e) {
                e.preventDefault();

                $(this).addClass('hide');
                $('#fresh-works').removeClass('hide')
            });
            $('.education-more').on('click', function (e) {
                e.preventDefault();

                $(this).addClass('hide');
                $('#fresh-educations').removeClass('hide')
            });
            $('.certificate-more').on('click', function (e) {
                e.preventDefault();

                $(this).addClass('hide');
                $('#fresh-certificates').removeClass('hide')
            });

            function onChangeCertificateType($certificateType) {
                var $scoreBoard = $certificateType.closest('form').find('.score-board');
                var val = $certificateType.val();
                if (val == '' || val == 'Others' || val == 'TESOL') {
                    $scoreBoard.find('input').prop('disabled', true);
                    $scoreBoard.addClass('hide');
                }
                else {
                    $scoreBoard.removeClass('hide');
                    if (val == 'TOEFL') {
                        $scoreBoard.find('input').prop('disabled', false).each(function () {
                            $(this).parent().parent().removeClass('hide');
                        });
                    }
                    else {
                        $scoreBoard.find('input').prop('disabled', true).each(function () {
                            $(this).parent().parent().addClass('hide');
                        });
                        if (val == 'TOEIC') {
                            $scoreBoard.find('input[name="meta[listening]"],input[name="meta[reading]"],input[name="meta[overall]"],input[name="meta[cefr]"]')
                                .prop('disabled', false).each(function () {
                                $(this).parent().parent().removeClass('hide');
                            });
                        }
                        else if (val == 'IELTS') {
                            $scoreBoard.find('input[name="meta[listening]"],input[name="meta[reading]"],input[name="meta[writing]"],input[name="meta[speaking]"]')
                                .prop('disabled', false).each(function () {
                                $(this).parent().parent().removeClass('hide');
                            });
                        }
                    }
                }
            }
            $('.inputFreshCertificateType').on('change', function () {
                onChangeCertificateType($(this));
            });
            $('#user-certificates button[type="reset"]').on('click', function () {
                var $this = $(this);
                setTimeout(function () {
                    onChangeCertificateType($this.closest('form').find('.inputFreshCertificateType'));
                }, 400);
            });
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('profile_content')
    <form method="post" action="{{ homeUrl('profile/professional-skills') }}">
        {{ csrf_field() }}
        @if (count($errors->professional_skills) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->professional_skills->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label for="inputProfessionalSkills" class="sr-only">{{ trans_choice('label.professional_skill', 2) }}</label>
            <select class="form-control select2" id="inputProfessionalSkills" name="professional_skills[]" required multiple="multiple"
                    data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.professional_skill_lc', 2) }}" style="width: 100%;">
                @foreach($professional_skills as $professional_skill)
                    <option value="{{ $professional_skill->id }}"{{ in_array($professional_skill->id, $user_professional_skill_ids) ? ' selected' : '' }}>
                        {{ $professional_skill->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">{{ trans('form.action_save') }}</button>
        </div>
    </form>
@endsection
@section('profile_content_extra')
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{{ trans('label.work_history') }}</strong>
        </div>
        <div class="panel-body">
            @include('home_themes.wow_skype.pages.user._works')
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{{ trans('label.education_history') }}</strong>
        </div>
        <div class="panel-body">
            @include('home_themes.wow_skype.pages.user._educations')
        </div>
    </div>
    <div id="user-certificates" class="panel panel-default">
        <div class="panel-heading">
            <strong>{{ trans_choice('label.certificate', 2) }}</strong>
        </div>
        <div class="panel-body">
            @include('home_themes.wow_skype.pages.user._certificates')
        </div>
    </div>
@endsection