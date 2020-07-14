@extends('home_themes.wow_skype.master.simple')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('extended_styles')
    <style>
        #inputPhoneCode.select2-hidden-accessible {
            height: 0;
        }
        #phone-form-group .select2-selection--single {
            border: none;
            background-color: #eee;
            height: 32px;
        }
        #phone-form-group .select2-container--bootstrap .select2-selection {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            box-shadow: none;
        }
        #select2-inputPhoneCode-results-container .select2-dropdown {
            min-width: 261px;
            margin-left: 2px;
        }
        #select2-inputPhoneCode-results-container .select2-dropdown.select2-dropdown--below {
            margin-top: 1px;
        }
        #select2-inputPhoneCode-results-container .select2-dropdown.select2-dropdown--above {
            margin-top: -1px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            }).on('select2:open', function () {
                var $container = $('.select2-container');
                $container.attr('id', $container.find('.select2-results__options').attr('id') + '-container');
            });
        });
        var imNotARobot = function(){
            $("#submit-btn").prop('disabled', false);
        };
    </script>
@endsection
@section('main_content')
    <div id="page-student-sign-up">

        <div class="row">
            <div class="col-xs-12 col-md-2"></div>
            <div class="col-xs-12 col-sm-8">
                <div class="panel panel-default margin-top-20">
                    <div class="panel-heading text-center">
                        <h4 class="margin-none"><strong>{{ trans('label.student_register_heading') }}</strong></h4>
                    </div>
                    <div class="panel-body">
                        <p class="big text-center">{{ trans('label.student_register_help') }}</p>
                        <form method="post">
                            {{ csrf_field() }}
                            @if(!empty($agent_id))
                                <input type="hidden" name="agent_id" value="{{ $agent_id }}">
                            @endif
                            @if(!empty($teacher_id))
                                <input type="hidden" name="teacher_id" value="{{ $teacher_id }}">
                            @endif
                            @if(!empty($study_level))
                                <input type="hidden" name="study_level" value="{{ $study_level }}">
                            @endif
                            @if(!empty($study_problem))
                                <input type="hidden" name="study_problem" value="{{ $study_problem }}">
                            @endif
                            @if(!empty($study_course))
                                <input type="hidden" name="study_course" value="{{ $study_course }}">
                            @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <div class="form-group has-feedback">
                                <label for="inputDisplayName">{{ trans('label.full_name') }}</label>
                                <input class="form-control" id="inputDisplayName" type="text" placeholder="{{ trans('label.full_name') }}" name="display_name" required value="{{ old('display_name') }}">
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            </div>
                            <div id="phone-form-group" class="form-group has-feedback">
                                <label for="phone-number">{{ trans('label.phone') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon padding-none">
                                        <div style="width: 94px">
                                            <label for="inputPhoneCode" class="sr-only">{{ trans('label.calling_code') }}</label>
                                            <select id="inputPhoneCode" name="phone_code" class="form-control select2" data-placeholder="{{ trans('form.action_select') }} {{ trans('label.calling_code_lc') }}" style="width: 100%">
                                                {{ callingCodesAsOptions(old('phone_code', 'VN')) }}
                                            </select>
                                        </div>
                                    </span>
                                    <input type="tel" class="form-control" placeholder="{{ trans('label.phone') }}" id="phone-number" name="phone_number" required value="{{ old('phone_number') }}">
                                </div>
                                <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="email">{{ trans('label.email') }}</label>
                                <input class="form-control" id="email" type="email" placeholder="{{ trans('label.email') }}" name="email" required value="{{ old('email') }}">
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="password">{{ trans('label.password') }}</label>
                                <input class="form-control" type="password" id="password" placeholder="{{ trans('label.password') }}" name="password" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group col-xs-6 col-xs-offset-3">
                                <div class="g-recaptcha" data-callback="imNotARobot" data-sitekey="6Lc8BYcUAAAAABBzterynpA11TE8NIiGpqvAMftb"></div>
                            </div>

                            <button type="submit" disabled="disabled" id="submit-btn" class="btn btn-success btn-block">{{ trans('form.action_sign_up') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection