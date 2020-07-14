@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_salary_report_title'))
@section('page_description', trans('pages.admin_salary_report_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('salary-report') }}">{{ trans('pages.admin_salary_report_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('extended_styles')
    <style>
        .datepicker-dropdown.dropdown-menu {
            z-index: 2000;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/inputmask.binding.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            function renderReport(report) {
                var render = '';
                var order = 0;
                for (var i in report) {
                    render += renderReportItem(++order, report[i]);
                }
                return render;
            }

            function renderReportItem(order, reportItem) {
                return '<tr id="report-teacher-' + reportItem.teacher.id + '">' +
                    '<td class="text-center">' + order + '</td>' +
                    '<td class="text-center"><a target="_blank" href="' + reportItem.teacher.home_url + '"><i class="fa fa-external-link" </a></td>' +
                    '<td>' + reportItem.teacher.display_name + '</td>' +
                    '<td>' + reportItem.teacher.email + '</td>' +
                    '<td>' + reportItem.teacher.skype_id + '</td>' +
                    '<td>' + reportItem.teacher.phone + '</td>' +
                    '<td>' + reportItem.class_name + '</td>' +
                    '<td>' + reportItem.hours + '</td>' +
                    '<td>' + reportItem.hours_confirmed + '</td>' +
                    '<td>' + reportItem.salary_jump + '</td>' +
                    '<td>' + reportItem.total + '</td>' +
                    '</tr>';
            }

            $('.month-picker').datepicker({
                format: '{{ $month_js_format }}',
                language: '{{ $site_locale }}',
                startView: 'months',
                minViewMode: 'months',
                enableOnReadonly: false
            });

            var _$inputYearMonth = $('[name="filter_month_year"]');
            var _$report = $('#report');
            var _$reportExport = $('#report-export');
            var _reportUrl = '?export=1&month_year=';
            var _$reportJump = $('#report-apply-jump');
            var _$reportTime = $('#report-apply-time');
            var _$reportNone = $('#report-none');
            var _$reportLoading = $('#report-loading');
            $('#report-calculate').on('click', function (e) {
                e.preventDefault();
                _$report.addClass('hide');
                _$reportNone.addClass('hide');
                _$reportLoading.removeClass('hide');
                var inputYearMonth = _$inputYearMonth.val();
                var api = new KatnissApi(true);
                api.get('admin/salary-report', {
                    month_year: inputYearMonth
                }, function (failed, data, messages) {
                    if (failed) {
                        _$reportNone.removeClass('hide');
                        _$reportExport.addClass('hide');
                    }
                    else {
                        _$reportTime.text(inputYearMonth);
                        _$reportJump.text(data.jump);
                        _$report.find('tbody').empty().html(renderReport(data.report));
                        _$report.removeClass('hide');
                        _$reportExport.attr('href', _reportUrl + inputYearMonth).removeClass('hide');
                    }
                }, function () {
                    _$reportNone.removeClass('hide');
                    _$reportExport.addClass('hide');
                }, function () {
                    _$reportLoading.addClass('hide');
                });
            });
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            {{--<div class="box box-primary">--}}
                {{--<div class="box-body">--}}
                    {{--<form method="post" action="{{ adminUrl('salary-report') }}">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<input type="hidden" name="currency" value="{{ $salary_jump_currency }}">--}}
                        {{--<div class="row">--}}
                            {{--<div class="form-group col-xs-12 col-sm-4">--}}
                                {{--<label for="inputJump">{{ trans('label.salary_jump') }}</label>--}}
                                {{--<div class="input-group">--}}
                                    {{--<input type="text" placeholder="{{ trans('label.salary_jump') }}"--}}
                                           {{--value="{{ $salary_jump_value }}"--}}
                                           {{--class="form-control" id="inputJump" name="jump" required--}}
                                           {{--data-inputmask="'alias':'decimal','radixPoint':'{{ $number_format_chars[0] }}','groupSeparator':'{{ $number_format_chars[1] }}','autoGroup':true,'integerDigits':6,'digits':2,'digitsOptional':false,'placeholder':'0{{ $number_format_chars[0] }}00'">--}}
                                    {{--<span class="input-group-addon">--}}
                                        {{--{{ $salary_jump_currency }} / 1 {{ trans_choice('label.hour_lc', 1) }}--}}
                                    {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-xs-12 col-sm-4">--}}
                                {{--<label for="inputApplyFrom">{{ trans('label.apply_from') }}</label>--}}
                                {{--<input type="text" placeholder="{{ trans('label.apply_from') }}"--}}
                                       {{--value="{{ $salary_jump_apply_from }}"--}}
                                       {{--class="form-control month-picker" name="apply_from" id="inputApplyFrom" required>--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-xs-12 col-sm-4">--}}
                                {{--<label class="hidden-xs">&nbsp;</label>--}}
                                {{--<div>--}}
                                    {{--<button type="submit"--}}
                                            {{--class="btn btn-primary">{{ trans('form.action_update') }}</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
            <!-- /.box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="filter_month_year" class="form-control month-picker"
                                       placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.month_lc', 1) }}">
                                <div class="input-group-btn">
                                    <button id="report-calculate" type="button" class="btn btn-primary">
                                        <i class="fa fa-cog"></i> {{ trans('form.action_calculate') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a id="report-export" role="button" target="_blank" href="#" class="btn btn-success hide">
                                <i class="fa fa-download"></i> {{ trans('form.action_export') }}
                            </a>
                        </div>
                    </div>
                </div><!-- /.box-header -->
                <div id="report" class="box-body table-responsive no-padding hide">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th class="order-col-1"></th>
                            <th>{{ trans('label.display_name') }}</th>
                            <th>{{ trans('label.email') }}</th>
                            <th>Skype ID</th>
                            <th>{{ trans('label.phone') }}</th>
                            <th>{{ trans('label.classroom') }}</th>
                            <th>{{ trans('label.teaching_hours') }}</th>
                            <th>{{ trans('label.hours_confirmed') }}</th>
                            <th>{{ $salary_jump_currency }} / 1 {{ trans_choice('label.hour_lc', 1) }}</th>
                            <th>{{ trans('label.total') }} ({{ settings()->currency }})</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th class="order-col-1"></th>
                            <th>{{ trans('label.display_name') }}</th>
                            <th>{{ trans('label.email') }}</th>
                            <th>Skype ID</th>
                            <th>{{ trans('label.phone') }}</th>
                            <th>{{ trans('label.classroom') }}</th>
                            <th>{{ trans('label.teaching_hours') }}</th>
                            <th>{{ trans('label.hours_confirmed') }}</th>
                            <th>{{ $salary_jump_currency }} / 1 {{ trans_choice('label.hour_lc', 1) }}</th>
                            <th>{{ trans('label.total') }} ({{ settings()->currency }})</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div id="report-none" class="box-body hide">
                    {{ trans('label.list_empty') }}
                </div>
                <div id="report-loading" class="overlay hide">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection