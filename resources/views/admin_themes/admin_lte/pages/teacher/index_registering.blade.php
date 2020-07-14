@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_registering_teachers_title'))
@section('page_description', trans('pages.admin_registering_teachers_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('registering-teachers') }}">{{ trans('pages.admin_registering_teachers_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_put($('a.approve'), '{{ trans('form.action_approve') }}', '{{ trans('label.wanna_approve', ['name' => '']) }}');
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
                    </div>
                </div><!-- /.box-header -->
                @if($teachers->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.display_name') }}</th>
                                    <th>{{ trans('label.email') }}</th>
                                    <th>Skype ID</th>
                                    <th>{{ trans('label.phone') }}</th>
                                    <th>{{ trans('label.status') }}</th>
                                    <th>{{ trans('label.created_at') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.display_name') }}</th>
                                    <th>{{ trans('label.email') }}</th>
                                    <th>Skype ID</th>
                                    <th>{{ trans('label.phone') }}</th>
                                    <th>{{ trans('label.status') }}</th>
                                    <th>{{ trans('label.created_at') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td>{{ $teacher->userProfile->display_name }}</td>
                                        <td>{{ $teacher->userProfile->email }}</td>
                                        <td>{{ $teacher->userProfile->skype_id }}</td>
                                        <td>{{ $teacher->userProfile->phone }}</td>
                                        <td>
                                            @if($teacher->status == \Katniss\Everdeen\Models\Teacher::REQUESTED)
                                                <span class="label label-warning">{{ trans('label.status_requested') }}</span>
                                            @elseif($teacher->status == \Katniss\Everdeen\Models\Teacher::REJECTED)
                                                <span class="label label-danger">{{ trans('label.status_rejected') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $teacher->created_at }}</td>
                                        <td>
                                            <a href="{{ adminUrl('teachers/{id}/edit', ['id'=> $teacher->user_id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="approve" href="{{ addRdrUrl(adminUrl('teachers/{id}', ['id'=> $teacher->user_id]) . '?approve=1') }}">
                                                {{ trans('form.action_approve') }}
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