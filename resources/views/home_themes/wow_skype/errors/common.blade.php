@extends('home_themes.wow_skype.master.master')
@section('main_content')
    <div id="page-error">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2>{{ trans('label.error') }} {{ $code }}</h2>
                @if(!empty($message))
                    <p class="help-block">{{ $message }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection