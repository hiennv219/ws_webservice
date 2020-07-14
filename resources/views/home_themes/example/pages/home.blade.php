@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="intro" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ $site_name }}</h1>
                    @if(!$is_auth)
                        <p>
                            <a href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
                        </p>
                    @else
                        <p>
                            {{ trans('label._hi', ['name' => $auth_user->display_name]) }}
                        </p>
                        @if($auth_user->can('access-admin'))
                            <p>
                                <a href="{{ adminUrl() }}">{{ trans('form.action_go_to') }} {{ trans('pages.admin_title') }}</a>
                            </p>
                        @endif
                        <p>
                            <a href="{{ homeUrl('auth/logout') }}">{{ trans('form.action_logout') }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection