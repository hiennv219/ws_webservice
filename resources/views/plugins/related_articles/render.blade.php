@if($articles->count()>0)
    <h4 class="color-master bold-600 uppercase margin-top-none">
        {{ trans('related_articles.title') }}
    </h4>
    <div class="row margin-h--5">
        @foreach($articles as $article)
            <div class="col-xs-12 col-sm-6 col-md-4 padding-h-5 margin-bottom-10">
                <div class="related-article-item bg-master">
                    <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                        <div class="image-cover height-120 border-solid border-master border-3x border-bottom-none"
                             style="background-image: url({{ empty($article->featured_image) ? $default_image : $article->featured_image }})">
                        </div>
                    </a>
                    <div class="padding-v-5 padding-h-10">
                        <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                            <span class="color-white uppercase bold-600">{{ $article->title }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif