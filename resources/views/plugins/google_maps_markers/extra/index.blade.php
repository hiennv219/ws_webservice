@section('extended_scripts')
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="margin-bottom">
            <a class="btn btn-primary" href="{{ addExtraUrl('admin/google-maps-markers/create', adminUrl('extra')) }}">
                {{ trans('form.action_add') }} {{ trans_choice('google_maps_markers.map_marker_lc', 1) }}
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
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('google_maps_markers.map_marker', 2)]) }}</h3>
            </div><!-- /.box-header -->
            @if($map_markers->count()>0)
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans('label.description') }}</th>
                            <th>{{ trans('google_maps_markers.data') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans('label.description') }}</th>
                            <th>{{ trans('google_maps_markers.data') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($map_markers as $map_marker)
                            <tr>
                                <td class="order-col-1">{{ ++$start_order }}</td>
                                <td>{{ $map_marker->name }}</td>
                                <td>{{ $map_marker->description }}</td>
                                <td>
                                    {{ trans('label.address') }}: {{ $map_marker->data->address }}<br>
                                    {{ trans('label.latitude') }}: {{ $map_marker->data->lat }}<br>
                                    {{ trans('label.longitude') }}: {{ $map_marker->data->lng }}
                                </td>
                                <td>
                                    <a href="{{ addExtraUrl('admin/google-maps-markers/id/edit', adminUrl('extra')) . '&id=' . $map_marker->id }}">
                                        {{ trans('form.action_edit') }}
                                    </a>
                                    <a class="delete"
                                       href="{{ addRdrUrl(addExtraUrl('admin/google-maps-markers/id', adminUrl('extra')) . '&id=' . $map_marker->id) }}">
                                        {{ trans('form.action_delete') }}
                                    </a>
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