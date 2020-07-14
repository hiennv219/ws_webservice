@extends('home_themes.wow_skype.master.simple')
@section('main_content')
    <div id="page-student-sign-up-step" class="text-center padding-top-30">
        <h2>{{ trans('label.thank_you_for_registering_to_learn') }}</h2>
        <div class="big">{{ trans('label.thank_you_for_registering_to_learn_help') }}</div>
        <a role="button" class="btn btn-success btn-lg margin-top-30 uppercase"
           href="{{ homeUrl('profile/account-information') }}">
            {{ trans('form.action_go_profile') }}
        </a>
    </div>
@endsection