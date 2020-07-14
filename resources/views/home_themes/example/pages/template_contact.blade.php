@if($post_template->showContactForm || $post_template->showMapMarker)
    <div class="row text-left">
        @if($post_template->showContactForm)
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ trans('example_theme.contact_template.contact_form') }}
                    </h3>
                </div>
                <div class="panel-body">
                    {{ $post_template->contactForm }}
                </div>
            </div>
        </div>
        @endif
        @if($post_template->showMapMarker)
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ trans('example_theme.contact_template.map_marker') }}
                    </h3>
                </div>
                <div class="panel-body">
                    {{ $post_template->mapMarker }}
                </div>
            </div>
        </div>
        @endif
    </div>
@else
    <div class="alert alert-danger">{{ trans('example_theme.must_activate_extensions_for_contact_template') }}</div>
@endif