@extends('home_themes.wow_skype.master.master_footer_placed')
@section('footer')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-4">
                <div class="margin-bottom-10">
                    <img class="logo" src="{{ themeImageAsset('logo.png') }}">
                </div>
                <div>
                    @if(!empty($home_description))
                        <p><em>{{ $home_description }}</em></p>
                    @endif
                    <p class="bold-700">
                        {{ !empty($home_email) ? trans('label.email_short') . ': ' . $home_email : '' }}<br>
                        {{ !empty($home_hot_line) ? trans('label.hot_line_short') . ': ' . $home_hot_line : '' }}
                    </p>
                </div>
            </div>
            <div class="col-sm-8">
                {!! placeholder('footer_links') !!}
                <div class="pull-right text-center width-150 min-width-sm-full">
                    <div class="margin-bottom-5 margin-top-10">
                        <a class="btn btn-primary btn-block uppercase bold-700"
                           href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
                    </div>
                    <div class="margin-bottom-10">
                        <a href="{{ homeUrl('user/sign-up') }}">
                            <span class="color-normal">{!! trans('label.or_sign_up_here') !!}</span>
                        </a>
                    </div>
                    <ul class="social-links list-inline">
                        @if(!empty($social_facebook))
                            <li>
                                <a target="_blank" href="{{ $social_facebook }}">
                                    <div class="box-32 box-circle box-center bg-facebook">
                                        <span class="color-white"><i class="fa fa-facebook"></i></span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(!empty($social_twitter))
                            <li>
                                <a target="_blank" href="{{ $social_twitter }}">
                                    <div class="box-32 box-circle box-center bg-twitter">
                                        <span class="color-white"><i class="fa fa-twitter"></i></span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(!empty($social_instagram))
                            <li>
                                <a target="_blank" href="{{ $social_instagram }}">
                                    <div class="box-32 box-circle box-center bg-instagram">
                                        <span class="color-white"><i class="fa fa-instagram"></i></span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(!empty($social_gplus))
                            <li>
                                <a target="_blank" href="{{ $social_gplus }}">
                                    <div class="box-32 box-circle box-center bg-gplus">
                                        <span class="color-white"><i class="fa fa-google-plus"></i></span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(!empty($social_youtube))
                            <li>
                                <a target="_blank" href="{{ $social_youtube }}">
                                    <div class="box-32 box-circle box-center bg-youtube">
                                        <span class="color-white"><i class="fa fa-youtube-play"></i></span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(!empty($social_skype))
                            <li>
                                <a target="_blank" href="skype:{{ $social_skype }}?chat">
                                    <div class="box-32 box-circle box-center bg-skype">
                                        <span class="color-white"><i class="fa fa-skype"></i></span>
                                    </div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection