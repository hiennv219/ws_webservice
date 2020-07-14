@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="pages" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">
                        @if(!empty($category))
                            {{ trans_choice('label.category', 1) }}: <em>{{ $category->name }}</em>
                        @else
                            {{ trans_choice('label.article', 2) }}
                        @endif
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    {{ placeholder('articles', null, null, trans('label.no_widget')) }}
                </div>
                <div class="col-sm-9 text-left">
                    @if($articles->count() > 0)
                        @foreach($articles as $article)
                            <div id="article-{{ $article->id }}">
                                <h3>
                                    <a href="{{ homeUrl('example/articles/{id}', ['id' => $article->id]) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                                <article>{!! htmlShorten($article->content) !!}</article>
                            </div>
                        @endforeach
                        {{ $articles->links() }}
                    @else
                        {{ trans('label.list_empty') }}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection