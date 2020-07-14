@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_helps_title'))
@section('page_description', trans('pages.admin_helps_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('helps') }}">{{ trans('pages.admin_helps_title') }}</a></li>
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
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('helps/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.help_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.help_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
                @if($helps->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.title') }}</th>
                                    <th>{{ trans('label.slug') }}</th>
                                    <th>{{ trans_choice('label.category', 1) }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.title') }}</th>
                                    <th>{{ trans('label.slug') }}</th>
                                    <th>{{ trans_choice('label.category', 1) }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($helps as $help)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td class="text-center">
                                            <a href="{{ homeUrl('helps/{slug}', ['slug' => $help->slug]) }}">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        </td>
                                        <td>{{ $help->title }}</td>
                                        <td>{{ $help->slug }}</td>
                                        <td>{{ $help->categories()->first()->name }}</td>
                                        <td>
                                            <a href="{{ adminUrl('helps/{id}/edit', ['id'=> $help->id]) }}">
                                              {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('helps/{id}', ['id'=> $help->id])) }}">
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
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection