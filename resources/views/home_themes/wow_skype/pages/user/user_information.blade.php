@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-user-information',
    'panel_heading' => trans('label.user_information'),
])
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
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
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.date-picker').datepicker({
                format: '{{ $date_js_format }}',
                language: '{{ $site_locale }}',
                enableOnReadonly : false
            });
            $('.select2').select2({
                theme: 'bootstrap'
            }).on('select2:open', function () {
                var $container = $('.select2-container');
                $container.attr('id', $container.find('.select2-results__options').attr('id') + '-container');
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
            <label for="inputDisplayName" class="control-label">{{ trans('label.full_name') }}</label>
            <input type="text" placeholder="{{ trans('label.full_name') }}" value="{{ $auth_user->display_name }}"
                   class="form-control" id="inputDisplayName" name="display_name" required>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="inputBirthday" class="control-label">{{ trans('label.birthday') }} ({{ $date_js_format }})</label>
                    <input type="text" placeholder="{{ trans('label.birthday') }}" value="{{ $auth_user->birthday }}"
                           class="form-control date-picker" name="date_of_birth" id="inputBirthday" required>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="inputGender" class="control-label">{{ trans('label.gender') }}</label>
                    <select id="inputGender" class="form-control" name="gender" required>
                        <option value="">
                            - {{ trans('form.action_select') }} {{ trans('label.gender') }} -
                        </option>
                        @foreach(allGenders() as $gender)
                            <option value="{{ $gender }}"{{ $gender == $auth_user->gender ? ' selected' : '' }}>
                                {{ trans('label.gender_'.$gender) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div id="phone-form-group" class="form-group">
            <label for="inputPhoneNumber" class="control-label">{{ trans('label.phone') }}</label>
            <div class="input-group">
                <span class="input-group-addon padding-none">
                    <div style="width: 94px">
                        <label for="inputPhoneCode" class="sr-only">{{ trans('label.calling_code') }}</label>
                        <select id="inputPhoneCode" name="phone_code" class="form-control select2" data-placeholder="{{ trans('form.action_select') }} {{ trans('label.calling_code_lc') }}" style="width: 100%">
                            {{ callingCodesAsOptions($auth_user->phone_code) }}
                        </select>
                    </div>
                </span>
                <input id="inputPhoneNumber" type="tel" class="form-control" placeholder="{{ trans('label.phone') }}"
                       name="phone_number" required value="{{ $auth_user->phone_number }}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress" class="control-label">{{ trans('label.address') }}</label>
            <input type="text" placeholder="{{ trans('label.address') }}" value="{{ $auth_user->address }}"
                   class="form-control" id="inputAddress" name="address" required>
        </div>
        <div class="form-group">
            <label for="inputCity" class="control-label">{{ trans('label.city') }}</label>
            <input type="text" placeholder="{{ trans('label.city') }}" value="{{ $auth_user->city }}"
                   class="form-control" id="inputCity" name="city" required>
        </div>
        <div class="form-group">
            <label for="inputCountry" class="control-label">{{ trans('label.country') }}</label>
            <select id="inputCountry" class="form-control select2" name="country" style="width: 100%;" required>
                <option value="">
                    - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                </option>
                {!! countriesAsOptions($auth_user->settings->country) !!}
            </select>
        </div>
        <div class="form-group">
            <label for="inputNationality" class="control-label">{{ trans('label.nationality') }}</label>
            <select id="inputNationality" class="form-control select2" name="nationality" style="width: 100%;" required>
                <option value="">
                    - {{ trans('form.action_select') }} {{ trans('label.nationality') }} -
                </option>
                {!! countriesAsOptions($auth_user->nationality) !!}
            </select>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_save') }}</strong></button>
        </div>
    </form>
@endsection