@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_student_agents_title'))
@section('page_description', trans('pages.admin_student_agents_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('student-agents') }}">{{ trans('pages.admin_student_agents_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_add') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('extended_styles')
    <style>
        .select2-dropdown {
            min-width: 320px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.date-picker').datepicker({
                format: '{{ $date_js_format }}',
                language: '{{ $site_locale }}',
                enableOnReadonly : false
            });
            $('.select2').select2();
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.student_agent_lc', 1) }}</h3>
                </div>
                <form method="post" action="{{ adminUrl('student-agents') }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="required" for="inputDisplayName">{{ trans('label.display_name') }}</label>
                            <input class="form-control" id="inputDisplayName" name="display_name" maxlength="255"
                                   placeholder="{{ trans('label.display_name') }}" type="text" required value="{{ old('display_name') }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputEmail">{{ trans('label.email') }}</label>
                            <input class="form-control" id="inputEmail" name="email" maxlength="255" placeholder="{{ trans('label.email') }}" type="email" required value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputName">{{ trans('label.user_name') }}</label>
                            <input class="form-control" id="inputName" name="name" maxlength="255" placeholder="{{ trans('label.user_name') }}" type="text" required value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputPassword">{{ trans('label.password') }}</label>
                            <input class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}" type="text" required value="{{ old('password') }}">
                        </div>
                        <div class="form-group">
                            <label for="inputBirthday" class="control-label required">{{ trans('label.birthday') }} ({{ $date_js_format }})</label>
                            <input type="text" placeholder="{{ trans('label.birthday') }}" value="{{ old('date_of_birth') }}"
                                   class="form-control date-picker" name="date_of_birth" id="inputBirthday" required>
                        </div>
                        <div class="form-group">
                            <label for="inputGender" class="control-label required">{{ trans('label.gender') }}</label>
                            <select id="inputGender" class="form-control" name="gender" required>
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans('label.gender') }} -
                                </option>
                                @foreach(allGenders() as $gender)
                                    <option value="{{ $gender }}"{{ $gender == old('gender') ? ' selected' : '' }}>
                                        {{ trans('label.gender_'.$gender) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="inputPhoneCode" class="control-label required">{{ trans('label.calling_code') }}</label>
                                    <select id="inputPhoneCode" name="phone_code" class="form-control select2" style="width: 100%" required
                                            data-placeholder="- {{ trans('form.action_select') }} {{ trans('label.calling_code_lc') }} -">
                                        <option value="">
                                            - {{ trans('form.action_select') }} {{ trans('label.calling_code') }} -
                                        </option>
                                        {{ callingCodesAsOptions(old('phone_code')) }}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="inputPhoneNumber" class="control-label required">{{ trans('label.phone') }}</label>
                                    <input id="inputPhoneNumber" type="tel" class="form-control" placeholder="{{ trans('label.phone') }}"
                                           name="phone_number" required value="{{ old('phone_number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress" class="control-label">{{ trans('label.address') }}</label>
                            <input type="text" placeholder="{{ trans('label.address') }}" value="{{ old('address') }}"
                                   class="form-control" id="inputAddress" name="address">
                        </div>
                        <div class="form-group">
                            <label for="inputCity" class="control-label required">{{ trans('label.city') }}</label>
                            <input type="text" placeholder="{{ trans('label.city') }}" value="{{ old('city') }}"
                                   class="form-control" id="inputCity" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="inputCountry" class="control-label required">{{ trans('label.country') }}</label>
                            <select id="inputCountry" class="form-control select2" name="country" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans('label.country') }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                                </option>
                                {!! countriesAsOptions(old('country', '')) !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputNationality" class="control-label required">{{ trans('label.nationality') }}</label>
                            <select id="inputNationality" class="form-control select2" name="nationality" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans('label.nationality') }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans('label.nationality') }} -
                                </option>
                                {!! countriesAsOptions(old('nationality', '')) !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputSkypeId" class="control-label">Skype ID</label>
                            <input type="text" placeholder="Skype ID" value="{{ old('skype_id') }}"
                                   class="form-control" id="inputSkypeId" name="skype_id">
                        </div>
                        <div class="form-group">
                            <label for="inputFacebook" class="control-label">Facebook URL</label>
                            <input type="text" placeholder="Facebook URL" value="{{ old('facebook') }}"
                                   class="form-control" id="inputFacebook" name="facebook">
                        </div>
                        <div class="form-group">
                            <div class="checkbox icheck">
                                <label for="inputSendMail">
                                    <input id="inputSendMail" name="send_welcomed_mail" type="checkbox" value="1" checked>
                                    &nbsp; {{ trans('label.send_welcome_mail') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                        <div class="pull-right">
                            <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                            <a role="button" class="btn btn-warning" href="{{ adminUrl('student-agents') }}">{{ trans('form.action_cancel') }}</a>
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection