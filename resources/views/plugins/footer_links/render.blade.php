@if($links->count()>0)
    <div class="pull-left margin-right-30">
        @if(!empty($name))
            <h5 class="color-master uppercase bold-700">{{ $name }}</h5>
        @endif
        <ul class="list-unstyled">
            @foreach($links as $link)
                <li class="margin-bottom-10">
                    <a href="{{ $link->url }}">
                        <span class="color-normal">{{ $link->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif