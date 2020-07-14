@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_app_options_title'))
@section('page_description', trans('pages.admin_app_options_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('app-options') }}">{{ trans('pages.admin_app_options_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.app_option_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
                @if($options->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.key') }}</th>
                                <th>{{ trans('label.value') }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.key') }}</th>
                                <th>{{ trans('label.value') }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($options as $option)
                                <tr>
                                    <td class="order-col-1">{{ ++$start_order }}</td>
                                    <td>
                                        {{ $option->key }}
                                    </td>
                                    <td>
                                        {{ shorten($option->rawValue, $value_max_length) }}
                                    </td>
                                    <td>
                                        <a href="{{ adminUrl('app-options/{id}/edit', ['id'=> $option->id]) }}">{{ trans('form.action_edit') }}</a>
                                        <a class="delete" href="{{ addRdrUrl(adminUrl('app-options/{id}', ['id'=> $option->id])) }}">
                                            {{ trans('form.action_delete') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        {{ $pagination }}
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection