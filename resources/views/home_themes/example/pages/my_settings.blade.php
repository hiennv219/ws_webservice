@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="my-settings" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('pages.my_settings_title') }}</h1>
                    <p><strong>{{ trans('label.country') }}</strong><br>{{ $country }}</p>
                    <p><strong>{{ trans('label.language') }}</strong><br>{{ $locale }}</p>
                    <p><strong>{{ trans('label.timezone') }}</strong><br>{{ $timezone }}</p>
                    <p><strong>{{ trans('label.currency') }} &amp; {{ trans('label.number_format') }}</strong><br>{{ $price }}</p>
                    <p><strong>{{ trans('label.long_date_format') }} &amp; {{ trans('label.long_time_format') }}</strong><br>{{ $long_datetime }}</p>
                    <p><strong>{{ trans('label.short_date_format') }} &amp; {{ trans('label.short_time_format') }}</strong><br>{{ $short_datetime }}</p>
                    <p><a href="{{ meUrl('settings') }}">{{ trans('form.action_go_to') }} {{ trans('pages.my_settings_title') }}</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection