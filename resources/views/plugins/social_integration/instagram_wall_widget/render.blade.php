@if(!empty($instagram_user))
    <div id="{{ $html_id }}" class="widget-instagram-wall">
        @if(!empty($name))
            <h4>{{ $name }}</h4>
        @endif
        <div class="instagram-wall-wrapper">
            <div class="list clearfix hide">
            </div>
            <div class="form-group">
                <span class="hide">
                    <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                </span>
                <a role="button" class="btn btn-primary next hide" href="#"
                   data-widget-id="{{ $widget_id }}" data-max-id="">
                    {{ trans('instagram_wall.view_more') }}
                </a>
            </div>
        </div>
    </div>
@endif