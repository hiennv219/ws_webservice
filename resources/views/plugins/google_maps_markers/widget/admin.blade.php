@extends('plugins.default_widget.admin')
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
@section('extended_widget_top')
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="inputMapMarker">{{ trans_choice('google_maps_markers.map_marker', 1) }}</label>
                <select id="inputMapMarker" class="form-control select2" name="map_marker_id" style="width: 100%;">
                    <option value="0">[{{ trans('label.not_set') }}]</option>
                    @foreach($map_markers as $map_marker)
                        <option value="{{ $map_marker->id }}"{{ $map_marker_id == $map_marker->id ? ' selected' : '' }}>
                            {{ !empty($map_marker->name) ? $map_marker->name : $map_marker->data->address }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endsection
