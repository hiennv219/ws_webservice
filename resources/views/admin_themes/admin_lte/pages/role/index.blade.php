@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_roles_title'))
@section('page_description', trans('pages.admin_roles_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('roles') }}">{{ trans('pages.admin_roles_title') }}</a></li>
    </ol>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.role_lc', 2)]) }}</h3>
            </div><!-- /.box-header -->
            @if($roles->count()>0)
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="order-col-1">#</th>
                                <th>{{ trans('label.name') }}</th>
                                <th>{{ trans('label.display_name') }}</th>
                                <th>{{ trans_choice('label.permission', 2) }}</th>
                                <th>{{ trans('label.description') }}</th>
                                <th>{{ trans('label.status') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="order-col-1">#</th>
                                <th>{{ trans('label.name') }}</th>
                                <th>{{ trans('label.display_name') }}</th>
                                <th>{{ trans_choice('label.permission', 2) }}</th>
                                <th>{{ trans('label.description') }}</th>
                                <th>{{ trans('label.status') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td class="order-col-1">{{ ++$start_order }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->perms->implode('display_name', ', ') }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    @if($role->status == \Katniss\Everdeen\Models\Role::STATUS_HIDDEN)
                                        <span class="label label-default">{{ trans('label.status_hidden') }}</span>
                                    @elseif($role->status == \Katniss\Everdeen\Models\Role::STATUS_NORMAL)
                                        <span class="label label-info">{{ trans('label.status_normal') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    {{ $pagination }}
                </div>
            @else
                <div class="box-body">
                    {{ trans('label.list_empty') }}
                </div>
            @endif
        </div><!-- /.box -->
    </div>
</div>
@endsection