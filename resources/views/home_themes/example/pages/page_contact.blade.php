@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="pages" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans_choice('label.page', 2) }}</h1>
                    <div class="help-block small">Contact Page Template</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    {{ placeholder('pages', null, null, trans('label.no_widget')) }}
                </div>
                <div class="col-sm-9 text-left">
                    <div id="page-{{ $page->id }}">
                        <h3>{{ $page->title }}</h3>
                        <div class="help-block small">{{ trans('label.author') }}: {{ $page->author->display_name }} | {{ $page->created_at }}</div>
                        @if(!empty($page->featured_image))
                            <div class="thumbnail">
                                <img class="img-responsive" alt="{{ $page->title }}" src="{{ $page->featured_image }}">
                            </div>
                        @endif
                        <article>{!! contentFilter('post_content', $page->content) !!}</article>
                    </div>
                </div>
            </div>
            <hr>
            @include('home_themes.example.pages.template_contact')
        </div>
    </section>
@endsection