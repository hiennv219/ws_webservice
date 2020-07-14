@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_media_items_title'))
@section('page_description', trans('pages.admin_media_items_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('media-items') }}">{{ trans('pages.admin_media_items_title') }}</a></li>
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
                <a class="btn btn-primary" href="{{ adminUrl('media-items/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.media_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.media_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
            @if($media_items->count()>0)
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.title') }}</th>
                                <th>{{ trans('label.url') }}</th>
                                <th>{{ trans_choice('label.category', 2) }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.title') }}</th>
                                <th>{{ trans('label.url') }}</th>
                                <th>{{ trans_choice('label.category', 2) }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($media_items as $item)
                                <tr>
                                    <td class="order-col-2">{{ ++$start_order }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        <a class="open-window" href="{{ $item->url }}"
                                           data-name="_blank" data-width="800" data-height="600">
                                            <i class="fa fa-external-link"></i>
                                        </a> &nbsp;
                                        {{ $item->url }}
                                    </td>
                                    <td>{{ $item->categories->implode('name', ', ') }}</td>
                                    <td>
                                          <a href="{{ adminUrl('media-items/{id}/edit', ['id'=> $item->id]) }}">
                                              {{ trans('form.action_edit') }}
                                          </a>
                                          <a class="delete" href="{{ addRdrUrl(adminUrl('media-items/{id}', ['id'=> $item->id])) }}">
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