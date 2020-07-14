@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_classrooms_title'))
@section('page_description', trans('pages.admin_classrooms_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('opening-classrooms') }}">{{ trans('pages.admin_classrooms_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_add') }}</a></li>
</ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/inputmask.binding.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            function templateRender(item) {
                if (item.loading) return item.text;

                return '<div class="media">' +
                    '<div class="media-left"><img class="width-120" src="' + item.url_avatar_thumb + '"></div>' +
                    '<div class="media-body">' +
                    '<h4><strong>#' + item.id + ' - ' + item.display_name + '</strong> (' + item.name + ')</h4>' +
                    '<p>{{ trans('label.email') }}: ' + item.email + '.' +
                    '<br>Skype ID: ' + item.skype_id + '.' +
                    '<br>{{ trans('label.phone') }}: ' + item.phone + '.</p>'+
                    '</div>' +
                    '</div>';
            }

            function dataSelection(item) {
                return item.id != '' ? item.display_name + ' (' + item.email + ')' : item.text;
            }

            function dataMore(response) {
                return response._success
                    && response._data.pagination.last != 0
                    && response._data.pagination.last != response._data.pagination.current;
            }

            function initAjaxSelect2($selector, url, templateFunc, selectionFunc, resultFunc, moreFunc) {
                $selector.select2({
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            return {
                                results: resultFunc(data),
                                pagination: {
                                    more: moreFunc(data)
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: templateFunc, // omitted for brevity, see the source of this page
                    templateSelection: selectionFunc // omitted for brevity, see the source of this page
                });
            }

            initAjaxSelect2($('#inputTeacher'), KATNISS_WEB_API_URL + '/teachers', templateRender, dataSelection, function (response) {
                return response._success ? response._data.teachers : [];
            }, dataMore);
            initAjaxSelect2($('#inputStudent'), KATNISS_WEB_API_URL + '/students', templateRender, dataSelection, function (response) {
                return response._success ? response._data.students : [];
            }, dataMore);
            initAjaxSelect2($('#inputSupporter'), KATNISS_WEB_API_URL + '/supporters', templateRender, dataSelection, function (response) {
                return response._success ? response._data.supporters : [];
            }, dataMore);
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('classrooms') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.classroom_lc', 1) }}</h3>
                    </div>
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="inputName" class="control-label required">{{ trans('label.name') }}</label>
                            <input type="text" placeholder="{{ trans('label.name') }}" value="{{ old('name') }}"
                                   class="form-control" id="inputName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTeacher" class="control-label required">{{ trans_choice('label.teacher', 1) }}</label>
                            <select id="inputTeacher" class="form-control select2" name="teacher" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputStudent" class="control-label required">{{ trans_choice('label.student', 1) }}</label>
                            <select id="inputStudent" class="form-control select2" name="student" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.student', 1) }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans_choice('label.student', 1) }} -
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputSupporter" class="control-label required">{{ trans_choice('label.supporter', 1) }}</label>
                            <select id="inputSupporter" class="form-control select2" name="supporter" style="width: 100%;" required
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.supporter', 1) }} -">
                                <option value="">
                                    - {{ trans('form.action_select') }} {{ trans_choice('label.supporter', 1) }} -
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputDuration" class="control-label required">{{ trans('label.class_duration') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                                <input type="text" placeholder="{{ trans('label.class_duration') }}" value="{{ old('duration') }}"
                                       class="form-control" id="inputDuration" name="duration" required
                                       data-inputmask="'alias':'decimal','radixPoint':'{{ $number_format_chars[0] }}','groupSeparator':'{{ $number_format_chars[1] }}','autoGroup':true,'integerDigits':6,'digits':2,'digitsOptional':false,'placeholder':'0{{ $number_format_chars[0] }}00'">
                                <span class="input-group-addon">{{ trans_choice('label.hour_lc', 2) }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputDuration" class="control-label required">{{ trans('label.hours_salary') }}</label>
                            <div class="input-group">
                                <input type="text" placeholder="{{ trans('label.hours_salary') }}" value="{{ old('hours_salary') }}"  class="form-control" id="inputDuration" name="hours_salary" required
                                       data-inputmask="'alias':'decimal','radixPoint':'{{ $number_format_chars[0] }}','groupSeparator':'{{ $number_format_chars[1] }}','autoGroup':true,'integerDigits':6,'digits':2,'digitsOptional':false,'placeholder':'0{{ $number_format_chars[0] }}00'">
                                <span class="input-group-addon">{{ $salary_jump_currency }} / 1 {{ trans_choice('label.hour_lc', 1) }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputDuration" class="control-label">{{ trans('label.comment') }}</label>
                            <textarea placeholder="{{ trans('label.comment') }}" value="{{ old('comment') }}"
                                      class="form-control" id="inputClassComment" name="comment"></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                        <div class="pull-right">
                            <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                            <a role="button" class="btn btn-warning" href="{{ $redirect_url }}">{{ trans('form.action_cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection