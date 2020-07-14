@extends('home_themes.wow_skype.master.master')
@section('main_content')
    <div id="page-helps">
        <div class="row">
            <div class="col-sm-4">
                {{ $categories_menu }}
            </div>
            <div class="col-sm-8">
                @if(!empty($help))
                    <h1 class="margin-top-none margin-bottom-20 color-master uppercase">
                        <strong>{{ $help->title }}</strong>
                    </h1>
                    <article class="article-responsive">{!! $help->content !!}</article>
                @endif
            </div>
        </div>
    </div>
@endsection