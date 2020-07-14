@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="pages" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans_choice('label.page', 2) }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    {{ placeholder('pages', null, null, trans('label.no_widget')) }}
                </div>
                <div class="col-sm-9 text-left">
                    @if($pages->count() > 0)
                        @foreach($pages as $page)
                            <div id="page-{{ $page->id }}">
                                <h3>
                                    <a href="{{ homeUrl('example/pages/{id}', ['id' => $page->id]) }}">
                                        {{ $page->title }}
                                    </a>
                                </h3>
                                <article>{!! htmlShorten($page->content) !!}</article>
                            </div>
                        @endforeach
                        {{ $pages->links() }}
                    @else
                        {{ trans('label.list_empty') }}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection