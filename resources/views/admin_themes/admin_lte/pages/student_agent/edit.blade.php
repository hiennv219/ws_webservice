@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_student_agents_title'))
@section('page_description', trans('pages.admin_student_agents_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('student-agents') }}">{{ trans('pages.admin_student_agents_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_edit') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('extended_styles')
    <style>
        .select2-dropdown {
            min-width: 320px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('clipboard-js') }}"></script>
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            var clipboard = new Clipboard('.copy');
            clipboard.on('success', function(e) {
                x_modal_success('{{ trans('label.copied') }}');
            });
            $('.select-on-focus').on('focus', function () {
                $(this).select();
            });
            $('.date-picker').datepicker({
                format: '{{ $date_js_format }}',
                language: '{{ $site_locale }}',
                enableOnReadonly : false
            });
            $('.select2').select2();
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('student-agents/{id}', ['id'=> $user->id])) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a class="btn btn-success" href="{{ adminUrl('student-agents/{id}/students', ['id'=> $user->id]) }}">
                    {{ trans_choice('label.student', 2) }}
                </a>
                <a class="btn btn-primary pull-right" href="{{ adminUrl('student-agents/create') }}">{{ trans('form.action_add') }} {{ trans_choice('label.student_agent_lc', 1) }}</a>
            </div>
            <div class="margin-bottom form-inline form-group">
                <label for="inputUrl_{{ $user->id }}">{{ trans('label.student_sign_up_url') }}:</label>
                &nbsp;
                <a href="{{ homeUrl('student/sign-up') . '?agent=' . $user->id }}" target="_blank">
                    <i class="fa fa-external-link"></i>
                </a> &nbsp;
                <input id="inputUrl_{{ $user->id }}" type="text" class="form-control select-on-focus" readonly value="{{ homeUrl('student/sign-up') . '?agent=' . $user->id }}">
                <button type="button" class="btn btn-primary copy" data-clipboard-target="#inputUrl_{{ $user->id }}">{{ trans('form.action_copy') }}</button>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.student_agent_lc', 1) }} - <em>{{ $user->display_name }} ({{ $user->name }})</em>
                    </h3>
                </div>
                <form method="post" action="{{ adminUrl('student-agents/{id}', ['id'=> $user->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
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
                            <input class="form-control" id="inputDisplayName" name="display_name" maxlength="255" placeholder="{{ trans('label.display_name') }}" type="text" required value="{{ $user->display_name }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputEmail">{{ trans('label.email') }}</label>
                            <input class="form-control" id="inputEmail" name="email" maxlength="255" placeholder="{{ trans('label.email') }}" type="email" required value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputName">{{ trans('label.user_name') }}</label>
                            <input class="form-control" id="inputName" name="name" maxlength="255" placeholder="{{ trans('label.user_name') }}" type="text" required value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">{{ trans('label.password') }}</label>
                            <input class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="inputBirthday" class="control-label required">{{ trans('label.birthday') }} ({{ $date_js_format }})</label>
                            <input type="text" placeholder="{{ trans('label.birthday') }}" value="{{ $user->birthday }}"
                                   class="form-control date-picker" name="date_of_birth" id="inputBirthday" required>
                        </div>
                        <div class="form-group">
                            <label for="inputGender" class="control-label required">{{ trans('label.gender') }}</label>
                            <select id="inputGender" class="form-control" name="gender" required>
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans('label.gender') }} -
                                </option>
                                @foreach(allGenders() as $gender)
                                    <option value="{{ $gender }}"{{ $gender == $user->gender ? ' selected' : '' }}>
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
                                        {{ callingCodesAsOptions($user->phone_code) }}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="inputPhoneNumber" class="control-label required">{{ trans('label.phone') }}</label>
                                    <input id="inputPhoneNumber" type="tel" class="form-control" placeholder="{{ trans('label.phone') }}"
                                           name="phone_number" required value="{{ $user->phone_number }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress" class="control-label">{{ trans('label.address') }}</label>
                            <input type="text" placeholder="{{ trans('label.address') }}" value="{{ $user->address }}"
                                   class="form-control" id="inputAddress" name="address">
                        </div>
                        <div class="form-group">
                            <label for="inputCity" class="control-label required">{{ trans('label.city') }}</label>
                            <input type="text" placeholder="{{ trans('label.city') }}" value="{{ $user->city }}"
                                   class="form-control" id="inputCity" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="inputCountry" class="control-label required">{{ trans('label.country') }}</label>
                            <select id="inputCountry" class="form-control select2" name="country" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans('label.country') }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                                </option>
                                {!! countriesAsOptions($user->settings->country) !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputNationality" class="control-label required">{{ trans('label.nationality') }}</label>
                            <select id="inputNationality" class="form-control select2" name="nationality" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans('label.nationality') }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans('label.nationality') }} -
                                </option>
                                {!! countriesAsOptions($user->nationality) !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputSkypeId" class="control-label">Skype ID</label>
                            <input type="text" placeholder="Skype ID" value="{{ $user->skype_id }}"
                                   class="form-control" id="inputSkypeId" name="skype_id">
                        </div>
                        <div class="form-group">
                            <label for="inputFacebook" class="control-label">Facebook URL</label>
                            <input type="text" placeholder="Facebook URL" value="{{ $user->facebook }}"
                                   class="form-control" id="inputFacebook" name="facebook">
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                        <div class="pull-right">
                            <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                            <a role="button" class="btn btn-warning pull-right" href="{{ adminUrl('student-agents') }}">{{ trans('form.action_cancel') }}</a>
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