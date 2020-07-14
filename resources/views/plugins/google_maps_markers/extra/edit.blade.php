@section('extended_styles')
    <style>
        #map {
            display: block;
            width: 100%;
            height: 300px;
        }
    </style>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            var mapMarker = createGoogleMapsMarker($('#map'), {
                center: {
                    lat: {{ $map_marker->data->lat }},
                    lng: {{ $map_marker->data->lng }}
                },
                centerName: '{{ $map_marker->data->address }}',
                markCenter: true
            });
            mapMarker.enableClickToAddress(function () {
                $('[name="data[address]"]').val(mapMarker.clickResult.address);
                $('[name="data[lat]"]').val(mapMarker.clickResult.latLng.lat());
                $('[name="data[lng]"]').val(mapMarker.clickResult.latLng.lng());
                $('[name="data[place_id]"]').val(mapMarker.clickResult.placeId);

                x_modal_success('{{ trans('google_maps_markers.place_found') }}');
            }, function () {
                x_modal_alert('{{ trans('google_maps_markers.place_not_found') }}');
            });
            $('form').on('reset', function () {
                mapMarker.reset();

                $('[name="data[address]"]').val('{{ $map_marker->data->address }}');
                $('[name="data[lat]"]').val('{{ $map_marker->data->lat }}');
                $('[name="data[lng]"]').val('{{ $map_marker->data->lng }}');
                $('[name="data[place_id]"]').val('{{ $map_marker->data->place_id }}');
            }).on('submit', function () {
                if ($('[name="data[lat]"]').val() == '' || $('[name="data[lng]"]').val() == '') {
                    x_modal_alert('{{ trans('google_maps_markers.place_not_found') }}');
                    return false;
                }
            });
        });
    </script>
@endsection
<form method="post" action="{{ addRdrUrl(addExtraUrl('admin/google-maps-markers/id', adminUrl('extra')) . '&id=' . $map_marker->id) }}">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <input type="hidden" name="data[address]" value="{{ $map_marker->data->address }}">
    <input type="hidden" name="data[lat]" value="{{ $map_marker->data->lat }}">
    <input type="hidden" name="data[lng]" value="{{ $map_marker->data->lng }}">
    <input type="hidden" name="data[place_id]" value="{{ $map_marker->data->place_id }}">
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-warning delete" href="{{ addErrorUrl(addRdrUrl(addExtraUrl('admin/google-maps-markers/id', adminUrl('extra')) . '&id=' . $map_marker->id, addExtraUrl('admin/google-maps-markers', adminUrl('extra')))) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a class="btn btn-primary pull-right" href="{{ addExtraUrl('admin/google-maps-markers/create', adminUrl('extra')) }}">
                    {{ trans('form.action_add') }} {{ trans_choice('google_maps_markers.map_marker_lc', 1) }}
                </a>
            </div>
            <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('google_maps_markers.map_marker_lc', 1) }}</h4>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <div id="map"></div>
                <div class="help-block">{{ trans('google_maps_markers.map_help') }}</div>
            </div>
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                   @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                       <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                           <a href="#{{ localeInputId('tab', $locale) }}" data-toggle="tab">
                               {{ $properties['native'] }}
                           </a>
                       </li>
                   @endforeach
                </ul>
                <div class="tab-content">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="{{ localeInputId('tab', $locale) }}">
                            <?php
                                $trans = $map_marker->translate($locale);
                                $name = $trans ? $trans->name : '';
                                $description = $trans ? $trans->description : '';
                            ?>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                                       name="{{ localeInputName('name', $locale) }}" type="text"
                                       placeholder="{{ trans('label.name') }}" value="{{ $name }}">
                            </div>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                       name="{{ localeInputName('description', $locale) }}" type="text"
                                       placeholder="{{ trans('label.description') }}" value="{{ $description }}">
                            </div>
                        </div><!-- /.tab-pane -->
                    @endforeach
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
            <div class="margin-bottom">
                <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                <div class="pull-right">
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    <a role="button" class="btn btn-warning" href="{{ addExtraUrl('admin/google-maps-markers', adminUrl('extra')) }}">{{ trans('form.action_cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
