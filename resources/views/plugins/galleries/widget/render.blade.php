<div id="{{ $html_id }}" class="widget-galleries">
    @if(!empty($name))
        <h4>{{ $name }}</h4>
    @endif
    <div class="row">
        @foreach($photos as $photo)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="thumbnail">
                    <a href="{{ $photo->url }}" rel="{{ $html_id }}" title="{{ $photo->title }}">
                        <img src="{{ $photo->url }}" alt="{{ $photo->title }}" style="width:100%">
                        <div class="caption">{{ $photo->title }}</div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>