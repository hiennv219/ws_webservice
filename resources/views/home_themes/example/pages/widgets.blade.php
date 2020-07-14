@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="example-widgets" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('example_theme.example_widget') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    {{ placeholder('default_placeholder', KATNISS_EMPTY_STRING, KATNISS_EMPTY_STRING, trans('label.no_widget')) }}
                </div>
                <div class="col-xs-1"></div>
            </div>
        </div>
    </section>
@endsection