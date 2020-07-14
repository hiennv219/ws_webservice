@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="articles" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans_choice('label.article', 2) }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    {{ placeholder('articles', null, null, trans('label.no_widget')) }}
                </div>
                <div class="col-sm-9 text-left">
                    <div id="article-{{ $article->id }}">
                        <h3>{{ $article->title }}</h3>
                        <div class="help-block small">
                            {{ trans('label.author') }}: {{ $article->author->display_name }} |
                            {{ trans_choice('label.category', $article_categories->count()) }}:
                            {{ $article_categories->implode('name', ', ') }} |
                            {{ $article->created_at }}
                        </div>
                        @if(!empty($article->featured_image))
                            <div class="thumbnail">
                                <img class="img-responsive" alt="{{ $article->title }}" src="{{ $article->featured_image }}">
                            </div>
                        @endif
                        <article>{!! contentFilter('post_content', $article->content) !!}</article>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection