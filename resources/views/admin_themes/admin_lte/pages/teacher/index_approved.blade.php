@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_approved_teachers_title'))
@section('page_description', trans('pages.admin_approved_teachers_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('approved-teachers') }}">{{ trans('pages.admin_approved_teachers_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_put($('a.reject'), '{{ trans('form.action_reject') }}', '{{ trans('label.wanna_reject', ['name' => '']) }}');
            x_modal_put(
                $('a.full-schedule'),
                '{{ trans('form.action_change_to') }} {{ trans('label.status_full_schedule') }}',
                '{{ trans('label.wanna_change_to', ['name' => trans('label.status_full_schedule')]) }}');
            x_modal_put(
                $('a.available'),
                '{{ trans('form.action_change_to') }} {{ trans('label.status_teaching_available') }}',
                '{{ trans('label.wanna_change_to', ['name' => trans('label.status_teaching_available')]) }}');
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="search-modal-title">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputDisplayName" class="control-label">{{ trans('label.display_name') }}</label>
                                    <input id="inputDisplayName" type="text" class="form-control" value="{{ $search_display_name }}"
                                           name="display_name" placeholder="{{ trans('label.display_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">{{ trans('label.email') }}</label>
                                    <input id="inputEmail" type="text" class="form-control" value="{{ $search_email }}"
                                           name="email" placeholder="{{ trans('label.display_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputSkypeId" class="control-label">Skype ID</label>
                                    <input id="inputSkypeId" type="text" class="form-control" value="{{ $search_skype_id }}"
                                           name="skype_id" placeholder="Skype ID">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputPhoneNumber" class="control-label">{{ trans('label.phone') }}</label>
                                    <input id="inputPhoneNumber" type="text" class="form-control" value="{{ $search_phone_number }}"
                                           name="phone_number" placeholder="{{ trans('label.phone') }}">
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
                <a class="btn btn-primary" href="{{ addRdrUrl(adminUrl('teachers/create')) }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.teacher_lc', 1) }}
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
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.teacher_lc', 2)]) }}</h3>
                    <div class="box-tools">
                        <button type="button" class="btn {{ $on_searching ? 'btn-warning' : 'btn-primary' }} btn-sm" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i> {{ trans('form.action_search') }}
                        </button>
                        <a role="button" target="_blank" href="?export=1" class="btn btn-success btn-sm">
                            <i class="fa fa-download"></i> {{ trans('form.action_export') }}
                        </a>
                    </div>
                </div><!-- /.box-header -->
                @if($teachers->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.display_name') }}</th>
                                    <th>{{ trans('label.email') }}</th>
                                    <th>Skype ID</th>
                                    <th>{{ trans('label.phone') }}</th>
                                    <th>{{ trans('label.teaching_status') }}</th>
                                    <th>{{ trans('label.status_approved') }}</th>
                                    <th>{{ trans('form.action') }}</th>
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
                                    <th>{{ trans('label.teaching_status') }}</th>
                                    <th>{{ trans('label.status_approved') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td class="order-col-1 text-center">
                                            <a target="_blank" href="{{ homeUrl('teachers/{id}', ['id' => $teacher->user_id]) }}">
                                                {{ trans('label.link') }}
                                            </a>
                                            <a target="_blank" href="{{ adminUrl('teachers/{id}/review', ['id'=> $teacher->user_id]) }}">
                                                {{ trans('label.review') }}
                                            </a>
                                        </td>
                                        <td>{{ $teacher->userProfile->display_name }}</td>
                                        <td>{{ $teacher->userProfile->email }}</td>
                                        <td>{{ $teacher->userProfile->skype_id }}</td>
                                        <td>{{ $teacher->userProfile->phone }}</td>
                                        <td>
                                            @if($teacher->teaching_status == \Katniss\Everdeen\Models\Teacher::TEACHING_STATUS_AVAILABLE)
                                                <span class="label label-success">{{ trans('label.status_teaching_available') }}</span><br>
                                                <a class="full-schedule small" href="{{ addRdrUrl(adminUrl('teachers/{id}', ['id'=> $teacher->user_id]) . '?full_schedule=1') }}">
                                                    {{ trans('form.action_change_to') }} {{ trans('label.status_full_schedule') }}
                                                </a>
                                            @elseif($teacher->teaching_status == \Katniss\Everdeen\Models\Teacher::TEACHING_STATUS_FULL_SCHEDULE)
                                                <span class="label label-danger">{{ trans('label.status_full_schedule') }}</span><br>
                                                <a class="available small" href="{{ addRdrUrl(adminUrl('teachers/{id}', ['id'=> $teacher->user_id]) . '?available=1') }}">
                                                    {{ trans('form.action_change_to') }} {{ trans('label.status_teaching_available') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $teacher->approving_at }}</td>
                                        <td>
                                            <a href="{{ adminUrl('teachers/{id}/edit', ['id'=> $teacher->user_id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="reject" href="{{ addRdrUrl(adminUrl('teachers/{id}', ['id'=> $teacher->user_id]) . '?reject=1') }}">
                                                {{ trans('form.action_reject') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('teachers/{id}', ['id'=> $teacher->user_id])) }}">
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