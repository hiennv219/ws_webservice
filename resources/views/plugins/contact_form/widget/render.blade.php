<div id="{{ $html_id }}" class="widget-contact-form">
    <div class="panel panel-default">
        @if(!empty($name))
            <div class="panel-heading">
                <h4 class="panel-title">{{ $name }}</h4>
            </div>
        @endif
        <div class="panel-body">
            @if(!empty($description))
                <div class="help-block">{{ $description }}</div>
            @endif
            @include('plugins.contact_form.form')
        </div>
    </div>
</div>