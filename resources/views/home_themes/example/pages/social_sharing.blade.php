@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="social-sharing" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('example_theme.social_sharing') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>{{ isActivatedExtension('social_integration') ? trans('social_integration.ext_activated') : trans('social_integration.ext_not_activated') }}</p>
                    <p>
                        {!! contentPlace('sharing_buttons', [currentUrl()]) !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection