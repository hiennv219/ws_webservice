<div id="{{ $html_id }}" class="widget-base-links">
    @if(!empty($name))
        <h4>{{ $name }}</h4>
    @endif
    <ul class="list-unstyled">
        @foreach($links as $link)
            <li><a href="{{ $link->url }}" title="{{ $link->description }}">{{ $link->name }}</a></li>
        @endforeach
    </ul>
</div>