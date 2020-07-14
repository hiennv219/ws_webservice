@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_register_learning_requests_title'))
@section('page_description', trans('pages.admin_register_learning_requests_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('register-learning-requests') }}">{{ trans('pages.admin_learning_requests_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            function renderDetail(learningRequest, order) {
                var detail = '<p><strong>{{ trans('label.sort_order') }}:</strong> ' + order + '</p>' +
                    '<p>' +
                    '<strong>{{ trans_choice('label.student', 1) }}:</strong> ' +
                    '<a target="_blank" href="' + learningRequest.student.admin_edit_url + '">' + learningRequest.student.display_name + '</a>' +
                    '<br>- <em>{{ trans('label.phone') }}:</em> ' + learningRequest.student.phone +
                    '<br>- <em>{{ trans('label.email') }}:</em> ' + learningRequest.student.email +
                    '</p>' +
                    (learningRequest.teacher ? '<p>' +
                        '<strong>{{ trans_choice('label.teacher', 1) }}:</strong> ' +
                        '<a target="_blank" href="' + learningRequest.teacher.admin_edit_url + '">' + learningRequest.teacher.display_name + '</a>' +
                        '</p>' : '') +
                    (learningRequest.study_level ? '<p>' +
                        '<strong>{{ trans_choice('label.study_level', 1) }}:</strong> ' + learningRequest.study_level.name + '</p>' : '') +
                    (learningRequest.study_problem ? '<p>' +
                        '<strong>{{ trans_choice('label.study_problem', 1) }}:</strong> ' + learningRequest.study_problem.name + '</p>' : '') +
                    (learningRequest.study_course ? '<p>' +
                        '<strong>{{ trans_choice('label.study_course', 1) }}:</strong> ' + learningRequest.study_course.name + '</p>' : '');
                if(learningRequest.for_children) {
                    detail += '<p><strong>{{ trans('label.for_children') }}:</strong> {{ trans('label.yes') }}</p>' +
                        '<p><strong>{{ trans('label.your_children_full_name') }}:</strong> ' + learningRequest.children_full_name + '</p>' +
                        '<p><strong>{{ trans('label.your_children_age_range') }}:</strong> ' + learningRequest.age_range_label + '</p>' +
                        '<p><strong>Skype ID:</strong> ' + learningRequest.student.skype_id + '</p>' +
                        '<p><strong>{{ trans('label.your_children_learning_targets') }}:</strong> ' + learningRequest.learning_targets_label + '</p>' +
                        '<p><strong>{{ trans('label.your_children_learning_forms') }}:</strong> ' + learningRequest.learning_forms_label + '</p>';
                }
                else {
                    detail += '<p><strong>{{ trans('label.for_children') }}:</strong> {{ trans('label.no') }}</p>' +
                        '<p><strong>{{ trans('label.your_age_range') }}:</strong> ' + learningRequest.age_range_label + '</p>'+
                        '<p><strong>{{ trans_choice('label.professional_skill', 2) }}:</strong> ' + learningRequest.student.professional_skill_names + '</p>'+
                        '<p><strong>Skype ID:</strong> ' + learningRequest.student.skype_id + '</p>' +
                        '<p><strong>{{ trans('label.your_learning_targets') }}:</strong> ' + learningRequest.learning_targets_label + '</p>' +
                        '<p><strong>{{ trans('label.your_learning_forms') }}:</strong> ' + learningRequest.learning_forms_label + '</p>';
                }
                return detail;
            }

            $('.date-picker').datepicker({
                format: '{{ $date_js_format }}',
                language: '{{ $site_locale }}',
                enableOnReadonly : false
            });
            x_modal_put($('a.process'), '{{ trans('form.action_process') }}', '{{ trans('label.wanna_process', ['name' => '']) }}');

            var _$detailModal = $('#detail-modal');
            var _$detailModalLoading = $('#detail-modal-loading');
            var _$detailModalView = $('#detail-modal-view');
            $('a.view').on('click', function (e) {
                e.preventDefault();

                var $this = $(this);
                _$detailModal.modal('show');
                _$detailModalLoading.removeClass('hide');
                _$detailModalView.addClass('hide');

                var api = new KatnissApi(true);
                api.get('admin/learning-requests/' + $this.attr('data-id'), {}, function (failed, data, messages) {
                    if (!failed) {
                        _$detailModalView.html(renderDetail(data.learning_request, $this.attr('data-order')));
                    }
                    else {
                        _$detailModalView.html('<div class="alert alert-danger">{{ trans('error.fail_ajax') }}</div>');
                    }
                }, function () {
                    _$detailModalView.html('<div class="alert alert-danger">{{ trans('error.fail_ajax') }}</div>');
                }, function () {
                    _$detailModalLoading.addClass('hide');
                    _$detailModalView.removeClass('hide');
                });
            });
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
                                    <label for="inputCreatedAt" class="control-label">{{ trans('label.created_at') }}</label>
                                    <input id="inputCreatedAt" type="text" class="form-control date-picker" value="{{ $search_created_at }}"
                                           name="created_at" placeholder="{{ trans('label.created_at') }}">
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
    <div class="modal fade" id="detail-modal" tabindex="false" role="dialog" aria-labelledby="detail-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="detail-modal-title">{{ trans('form.action_view_detail') }}</h4>
                </div>
                <div id="detail-modal-content" class="modal-body">
                    <div id="detail-modal-loading">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                    </div>
                    <div id="detail-modal-view" class="hide">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                </div>
            </div>
        </div>
    </div>
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
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.learning_request_lc', 2)]) }}</h3>
                    <div class="box-tools">
                        <button type="button" class="btn {{ $on_searching ? 'btn-warning' : 'btn-primary' }} btn-sm" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i> {{ trans('form.action_search') }}
                        </button>
                    </div>
                </div><!-- /.box-header -->
                @php
                    $canDoAction = $auth_user->hasRole(['admin', 'manager']);
                @endphp
                @if($learning_requests->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.created_at') }}</th>
                                    <th>{{ trans_choice('label.student', 1) }}</th>
                                    <th>{{ trans_choice('label.teacher', 1) }}</th>
                                    <th>{{ trans('label.for_children') }}</th>
                                    <th>{{ trans_choice('label.study_level', 1) }}</th>
                                    <th>{{ trans_choice('label.study_problem', 1) }}</th>
                                    <th>{{ trans_choice('label.study_course', 1) }}</th>
                                    @if($canDoAction)
                                        <th>{{ trans('form.action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.created_at') }}</th>
                                    <th>{{ trans_choice('label.student', 1) }}</th>
                                    <th>{{ trans_choice('label.teacher', 1) }}</th>
                                    <th>{{ trans('label.for_children') }}</th>
                                    <th>{{ trans_choice('label.study_level', 1) }}</th>
                                    <th>{{ trans_choice('label.study_problem', 1) }}</th>
                                    <th>{{ trans_choice('label.study_course', 1) }}</th>
                                    @if($canDoAction)
                                        <th>{{ trans('form.action') }}</th>
                                    @endif
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($learning_requests as $learning_request)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td>{{ $learning_request->formattedCreatedAt }}</td>
                                        <td>
                                            <a target="_blank" href="{{ adminUrl('students/{id}/edit', ['id' => $learning_request->student_id]) }}">
                                                {{ $learning_request->studentUserProfile->display_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(!empty($learning_request->teacher_id))
                                                <a target="_blank" href="{{ adminUrl('teachers/{id}/edit', ['id' => $learning_request->teacher_id]) }}">
                                                    {{ $learning_request->teacherUserProfile->display_name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $learning_request->for_children == 1 ? trans('label.yes') : trans('label.no') }}</td>
                                        <td>
                                            @if(!empty($learning_request->study_level_id))
                                                {{ $learning_request->studyLevel->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($learning_request->study_problem_id))
                                                {{ $learning_request->studyProblem->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($learning_request->study_course_id))
                                                {{ $learning_request->studyCourse->name }}
                                            @endif
                                        </td>
                                        <td>
                                            <a class="view" data-id="{{ $learning_request->id }}" data-order="{{ $start_order }}"
                                               href="{{ adminUrl('learning-requests/{id}', ['id'=> $learning_request->id]) }}">
                                                {{ trans('form.action_view_detail') }}
                                            </a>
                                            @if($canDoAction)
                                                <a class="process" href="{{ addRdrUrl(adminUrl('learning-requests/{id}', ['id'=> $learning_request->id]) . '?process=1') }}">
                                                    {{ trans('form.action_process') }}
                                                </a>
                                            @endif
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