@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.action_edit') }}</h3>
            </div>
            <form method="post" action="{{ currentFullUrl() }}">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if($is_map_marker_enable)
                        <div class="form-group">
                            <label for="inputDefaultMapMarker">{{ trans('example_theme.default_map_marker') }}</label>
                            <select id="inputDefaultMapMarker" class="form-control select2" name="default_map_marker_id" style="width: 100%;">
                                <option value="0">[{{ trans('label.not_set') }}]</option>
                                @foreach($map_markers as $map_marker)
                                    <option value="{{ $map_marker->id }}"{{ $default_map_marker_id == $map_marker->id ? ' selected' : '' }}>
                                        {{ !empty($map_marker->name) ? $map_marker->name : $map_marker->data->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div>
                            {!! trans('example_theme.need_activate_map_marker', ['url' => adminUrl('extensions')]) !!}
                        </div>
                    @endif
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    </div>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>