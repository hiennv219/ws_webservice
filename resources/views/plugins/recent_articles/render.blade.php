<div id="{{ $html_id }}" class="widget-recent-articles">
    @if(!empty($name))
        <h4 class="bold-600 uppercase color-master">
            {{ $name }}
        </h4>
    @endif
    <ul class="list-unstyled">
        @foreach($articles as $article)
            <li class="margin-bottom-5">
                <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                    <i class="fa fa-caret-right font-20"></i> <span class="color-normal">{{ $article->title }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>