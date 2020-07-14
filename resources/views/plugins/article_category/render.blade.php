@if(!empty($category))
    <div id="{{ $html_id }}" class="widget-article-category">
        <h4 class="bold-600 uppercase color-master">
            <a href="{{ homeUrl('knowledge/categories/{slug}', ['slug' => $category->slug]) }}">
                {{ $category->name }}
            </a>
        </h4>
        @if($show_articles == 1)
            <ul class="list-unstyled">
                @foreach($articles as $article)
                    <li class="margin-bottom-5">
                        <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                            <i class="fa fa-caret-right font-20"></i> <span class="color-normal">{{ $article->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            @if(!empty($image))
                <a href="{{ homeUrl('knowledge/categories/{slug}', ['slug' => $category->slug]) }}">
                    <div class="image-cover height-180 border-solid border-master border-2x margin-v-10"
                         style="background-image: url({{ $image }})">
                    </div>
                </a>
            @endif
            <div class="text-justify">{!! $category->htmlDescription !!}</div>
        @endif
        @if($show_button == 1)
            <div class="text-center margin-top-20">
                <a class="btn btn-primary uppercase" href="{{ homeUrl('knowledge/categories/{slug}', ['slug' => $category->slug]) }}">
                    {{ trans('form.action_see_more') }}
                </a>
            </div>
        @endif
    </div>
@endif