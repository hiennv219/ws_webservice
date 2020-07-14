@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_opening_classrooms_title'))
@section('page_description', trans('pages.admin_opening_classrooms_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('classrooms') }}">{{ trans('pages.admin_classrooms_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
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
                return response._success && response._data.pagination.last != response._data.pagination.current;
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

            var $inputTeacher = $('#inputTeacher');
            initAjaxSelect2($inputTeacher, KATNISS_WEB_API_URL + '/teachers', templateRender, dataSelection, function (response) {
                return response._success ? response._data.teachers : [];
            }, dataMore);
            $inputTeacher.on('change', function () {
                $('#inputTeacherHidden').val($(this).val());
            });
            var $inputStudent = $('#inputStudent');
            initAjaxSelect2($inputStudent, KATNISS_WEB_API_URL + '/students', templateRender, dataSelection, function (response) {
                return response._success ? response._data.students : [];
            }, dataMore);
            $inputStudent.on('change', function () {
                $('#inputStudentHidden').val($(this).val());
            });
            var $inputSupporter = $('#inputSupporter');
            initAjaxSelect2($inputSupporter, KATNISS_WEB_API_URL + '/supporters', templateRender, dataSelection, function (response) {
                return response._success ? response._data.supporters : [];
            }, dataMore);
            $inputSupporter.on('change', function () {
                $('#inputSupporterHidden').val($(this).val());
            });

            x_modal_put($('a.classroom-close'), '{{ trans('form.action_close') }}', '{{ trans('label.wanna_close', ['name' => '']) }}');
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="search-modal" tabindex="false" role="dialog" aria-labelledby="search-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="search-modal-title">{{ trans('form.action_search') }}</h4>
                </div>
                <form>
                    <div id="search-modal-content" class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">{{ trans('label.name') }}</label>
                                    <input id="inputName" type="text" class="form-control" value="{{ $search_name }}"
                                           name="name" placeholder="{{ trans('label.name') }}">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="inputTeacher" class="control-label">{{ trans_choice('label.teacher', 1) }}</label>
                                    @if(!empty($search_teacher))
                                        <span class="small">({{ trans('label._current', ['current' => 'ID']) }}: {{ $search_teacher }})</span>
                                    @endif
                                    <select id="inputTeacher" class="form-control select2" style="width: 100%;"
                                            data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -">
                                    </select>
                                    <input id="inputTeacherHidden" type="hidden" name="teacher" value="{{ $search_teacher }}">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="inputStudent" class="control-label">{{ trans_choice('label.student', 1) }}</label>
                                    @if(!empty($search_student))
                                        <span class="small">({{ trans('label._current', ['current' => 'ID']) }}: {{ $search_student }})</span>
                                    @endif
                                    <select id="inputStudent" class="form-control select2" style="width: 100%;"
                                            data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.student', 1) }} -">
                                    </select>
                                    <input id="inputStudentHidden" type="hidden" name="student" value="{{ $search_student }}">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="inputSupporter" class="control-label">{{ trans_choice('label.supporter', 1) }}</label>
                                    @if(!empty($search_supporter))
                                        <span class="small">({{ trans('label._current', ['current' => 'ID']) }}: {{ $search_supporter }})</span>
                                    @endif
                                    <select id="inputSupporter" class="form-control select2" style="width: 100%;"
                                            data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.supporter', 1) }} -">
                                    </select>
                                    <input id="inputSupporterHidden" type="hidden" name="supporter" value="{{ $search_supporter }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                        <a role="button" class="btn btn-warning {{ $on_searching ? '' : 'hide' }}" href="{{ $clear_search_url }}">
                            {{ trans('form.action_clear_search') }}
                        </a>
                        <button type="submit" class="btn btn-primary">{{ trans('form.action_search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ addRdrUrl(adminUrl('classrooms/create')) }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.classroom_lc', 1) }}
                </a>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.classroom_lc', 2)]) }}</h3>
                    <div class="box-tools">
                        <button type="button" class="btn {{ $on_searching ? 'btn-warning' : 'btn-primary' }} btn-sm" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i> {{ trans('form.action_search') }}
                        </button>
                    </div>
                </div><!-- /.box-header -->
                @if($classrooms->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans_choice('label.teacher', 1) }}</th>
                                    <th>{{ trans_choice('label.student', 1) }}</th>
                                    <th>{{ trans_choice('label.supporter', 1) }}</th>
                                    <th>{{ trans('label.class_duration') }} ({{ trans_choice('label.hour_lc', 1) }})</th>
                                    <th>{{ trans('label.class_spent_time') }} ({{ trans_choice('label.hour_lc', 1) }})</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans_choice('label.teacher', 1) }}</th>
                                    <th>{{ trans_choice('label.student', 1) }}</th>
                                    <th>{{ trans_choice('label.supporter', 1) }}</th>
                                    <th>{{ trans('label.class_duration') }} ({{ trans_choice('label.hour_lc', 1) }})</th>
                                    <th>{{ trans('label.class_spent_time') }} ({{ trans_choice('label.hour_lc', 1) }})</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($classrooms as $classroom)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <th class="order-col-1 text-center">
                                            <a target="_blank" href="{{ homeUrl('classrooms/{id}', ['id' => $classroom->id]) }}">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        </th>
                                        <td>{{ $classroom->name }}</td>
                                        <td>{{ $classroom->teacherUserProfile->display_name }}</td>
                                        <td>{{ $classroom->studentUserProfile->display_name }}</td>
                                        <td>{{ $classroom->supporter->display_name }}</td>
                                        <td>{{ $classroom->duration }}</td>
                                        <td>{{ $classroom->spentTimeDuration }}</td>
                                        <td>
                                            <a href="{{ adminUrl('classrooms/{id}/edit', ['id'=> $classroom->id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="classroom-close" href="{{ addRdrUrl(adminUrl('classrooms/{id}', ['id'=> $classroom->id]) . '?close=1') }}">
                                                {{ trans('form.action_close') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('classrooms/{id}', ['id'=> $classroom->id])) }}">
                                                {{ trans('form.action_delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                             </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{ $pagination }}
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection