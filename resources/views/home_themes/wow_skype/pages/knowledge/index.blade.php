@extends('home_themes.wow_skype.master.master')
@section('before_main_content')
    <div id="knowledge-cover" class="bg-master">
        {!! contentPlace('knowledge_cover') !!}
    </div>
@endsection
@section('main_content')
    <div id="page-knowledge">
        <div id="knowledge-middle">
            {!! placeholder('knowledge_middle') !!}
        </div>
        <div id="knowledge-bottom" class="margin-v-30">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    {!! placeholder('knowledge_bottom_left') !!}
                </div>
                <div class="col-sm-6 col-md-4">
                    {!! placeholder('knowledge_bottom_middle') !!}
                </div>
                <div class="col-md-4">
                    {!! placeholder('knowledge_bottom_right') !!}
                </div>
            </div>
        </div>
    </div>
@endsection