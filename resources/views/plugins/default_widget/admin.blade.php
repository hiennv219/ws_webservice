@yield('extended_widget_top')

<!-- Custom Tabs -->
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
            <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                <a href="#tab_{{ $locale }}" data-toggle="tab">
                    {{ $properties['native'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab_{{ $locale }}">
                <div class="form-group">
                    <label for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                    <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                           name="{{ localeInputName('name', $locale) }}" type="text"
                           placeholder="{{ trans('label.name') }}" value="{{ $widget->getProperty('name', $locale) }}">
                </div>
                <div class="form-group">
                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                           name="{{ localeInputName('description', $locale) }}" type="text"
                           placeholder="{{ trans('label.description') }}" value="{{ $widget->getProperty('description', $locale) }}">
                </div>
                @if(isset($extended_localizing_path))
                    @include($extended_localizing_path)
                @endif
            </div><!-- /.tab-pane -->
        @endforeach
    </div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->

@yield('extended_widget_bottom')