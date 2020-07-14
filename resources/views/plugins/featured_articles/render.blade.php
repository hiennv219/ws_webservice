<div id="{{ $html_id }}" class="widget-featured-articles">
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
    @if($show_button==1)
        <div class="text-center margin-top-20">
            <a role="button" class="btn btn-primary uppercase" href="{{ homeUrl('knowledge/articles') }}">
                {{ trans('form.action_see_more') }}
            </a>
        </div>
    @endif
</div>