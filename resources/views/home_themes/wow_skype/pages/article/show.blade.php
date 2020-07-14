@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('medium-editor-insert-plugin/css/medium-editor-insert-plugin-frontend.css') }}">
@endsection
@section('main_content')
    <div id="page-article">
        <div class="row">
            <div class="col-md-8">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ homeUrl('knowledge') }}">{{ trans('pages.home_knowledge_title') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ homeUrl('knowledge/articles') }}">{{ trans('pages.home_articles_title') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $article->title }}</li>
                </ol>
                <h1 class="margin-top-none color-master margin-bottom-10 uppercase">
                    <strong>{{ $article->title }}</strong>
                </h1>
                @if(!empty($is_author) && $is_author && !$article->isPublished)
                    <div class="margin-bottom-10">
                        <span class="label label-warning">{{ trans('label.status_not_approved') }}</span>
                    </div>
                @endif
                <div class="master-slave-bar clearfix">
                    <div class="bar pull-left"></div>
                    <div class="bar pull-right"></div>
                </div>
                <div class="article-meta margin-v-10">
                    <a href="{{ homeUrl('knowledge/authors/{id}', ['id' => $article->author->id]) }}">
                        <img class="img-circle border-solid border-master width-30"
                             src="{{ $article->author->url_avatar_thumb }}">
                        {{ $article->author->display_name }}
                    </a>
                    <span class="color-lighter hidden-sm hidden-xs">
                        / {{ $article->diffDays == 0 ? trans('datetime.today') : $article->diffDays .' '. trans_choice('label.days_ago_lc', $article->diffDays) }}
                    </span>
                    <span class="color-lighter hidden-xs">
                        / {{ trans_choice('label.category', $article->categories->count()) }}:
                        <?php $lastId = $article->categories->last()->id; ?>
                        @foreach($article->categories as $category)
                            <a href="{{ homeUrl('knowledge/categories/{slug}', ['slug' => $category->slug]) }}">
                                {{ $category->name }}</a>{{ $category->id != $lastId ? ',' : '' }}
                        @endforeach
                    </span>
                </div>
                @if(!empty($article->featured_image))
                    <div class="image-cover margin-bottom-10">
                        <img src="{{ $article->featured_image }}">
                    </div>
                @endif
                <article class="article-responsive">{!! $article->content  !!}</article>
                @if($article->isPublished)
                    <div class="margin-top-20">
                        {!! contentPlace('sharing_buttons', [homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug])]) !!}
                    </div>
                @endif
                <div class="master-slave-bar margin-v-10 clearfix">
                    <div class="bar pull-left"></div>
                    <div class="bar pull-right"></div>
                </div>
                @if($article->isPublished)
                    <div class="comments">
                        {!! contentPlace('facebook_comments', [homeUrl('knowledge/articles/{slug}', ['slug' => $article->id], 'en')]) !!}
                    </div>
                    {!! contentPlace('article_after', [$article], '<div class="master-slave-bar margin-v-10 clearfix">
                            <div class="bar pull-left"></div>
                            <div class="bar pull-right"></div>
                        </div>') !!}
                @endif
            </div>
            <div class="col-md-4">
                @include('home_themes.wow_skype.pages.article.sidebar_right')
            </div>
        </div>
    </div>
@endsection