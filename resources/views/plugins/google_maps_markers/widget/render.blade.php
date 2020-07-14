@if(!empty($map_marker))
    <div id="{{ $html_id }}" class="widget-google-maps-markers default-google-maps-marker">
        @if(!empty($name))
            <h4>{{ $name }}</h4>
        @endif
        <div class="map"></div>
        @if(!empty($marker_description))
            <div class="help-block small">{{ $marker_description }}</div>
        @endif
    </div>
@endif