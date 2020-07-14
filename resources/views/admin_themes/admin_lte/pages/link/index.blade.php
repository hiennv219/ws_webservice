@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_links_title'))
@section('page_description', trans('pages.admin_links_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('links') }}">{{ trans('pages.admin_links_title') }}</a></li>
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
                <a class="btn btn-primary" href="{{ adminUrl('links/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.link_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.link_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
            @if($links->count()>0)
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.name') }}</th>
                                <th>{{ trans('label.url') }}</th>
                                <th>{{ trans('label.picture') }}</th>
                                <th>{{ trans_choice('label.category', 2) }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.name') }}</th>
                                <th>{{ trans('label.url') }}</th>
                                <th>{{ trans('label.picture') }}</th>
                                <th>{{ trans_choice('label.category', 2) }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($links as $link)
                                <tr>
                                    <td class="order-col-2">{{ ++$start_order }}</td>
                                    <td>{{ $link->name }}</td>
                                    <td>
                                        <a class="open-window" href="{{ $link->url }}"
                                           data-name="_blank" data-width="800" data-height="600">
                                            <i class="fa fa-external-link"></i>
                                        </a> &nbsp;
                                        {{ $link->url }}
                                    </td>
                                    <td>
                                        @if(!empty($link->image))
                                            <a class="open-window" href="{{ $link->image }}"
                                                data-name="_blank" data-width="800" data-height="600">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $link->categories->implode('name', ', ') }}</td>
                                    <td>
                                          <a href="{{ adminUrl('links/{id}/edit', ['id'=> $link->id]) }}">
                                              {{ trans('form.action_edit') }}
                                          </a>
                                          <a class="delete" href="{{ addRdrUrl(adminUrl('links/{id}', ['id'=> $link->id])) }}">
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