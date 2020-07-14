@extends('home_themes.wow_skype.master.simple')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
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
            $('#inputOtherLearningTarget').on('change', function () {
                if($(this).is(':checked')) {
                    $('#inputOtherLearningTargets').removeClass('hide').prop('required', true);
                }
                else {
                    $('#inputOtherLearningTargets').addClass('hide').prop('required', false);
                }
            }).trigger('change');
            $('#inputOtherLearningForm').on('change', function () {
                if($(this).is(':checked')) {
                    $('#inputOtherLearningForms').removeClass('hide').prop('required', true);
                }
                else {
                    $('#inputOtherLearningForms').addClass('hide').prop('required', false);
                }
            }).trigger('change');
            $('#inputOtherLearningTarget_children').on('change', function () {
                if($(this).is(':checked')) {
                    $('#inputOtherLearningTargets_children').removeClass('hide').prop('required', true);
                }
                else {
                    $('#inputOtherLearningTargets_children').addClass('hide').prop('required', false);
                }
            }).trigger('change');
            $('#inputOtherLearningForm_children').on('change', function () {
                if($(this).is(':checked')) {
                    $('#inputOtherLearningForms_children').removeClass('hide').prop('required', true);
                }
                else {
                    $('#inputOtherLearningForms_children').addClass('hide').prop('required', false);
                }
            }).trigger('change');
            $('[name="switch_children"]').on('change', function () {
                if($(this).is(':checked')) {
                    $('#adult-form').addClass('hide');
                    $('#children-form').removeClass('hide');
                }
                else {
                    $('#adult-form').removeClass('hide');
                    $('#children-form').addClass('hide');
                }
            });
        });
    </script>
@endsection
@section('main_content')
    <div id="page-student-sign-up-step">
        <div class="row">
            <div class="col-xs-12 col-md-2"></div>
            <div class="col-xs-12 col-sm-8">
                <div class="panel panel-default margin-top-20">
                    <div class="panel-heading text-center">
                        <h4 class="margin-none"><strong>{{ trans('label.student_register_heading') }}</strong></h4>
                    </div>
                    <div class="panel-body">
                        <p class="big text-center margin-bottom-20">{{ trans('label.student_register_step2_help') }}</p>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="switch_children"{{ old('for_children') == 1 ? ' checked' : '' }}>
                                    <strong>{{ trans('label.for_children') }}</strong>
                                </label>
                            </div>
                        </div>
                        @include('home_themes.wow_skype.pages.student.sign_up_step_2_adult')
                        @include('home_themes.wow_skype.pages.student.sign_up_step_2_children')
                    </div>
                </div>
            </div>
        </div>
        <div class="margin-v-20">&nbsp;</div>
    </div>
@endsection