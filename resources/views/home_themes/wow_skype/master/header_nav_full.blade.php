<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ homeUrl() }}">
                <img class="logo" src="{{ themeImageAsset('logo.png') }}">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="main-menu">
            {{ $main_menu }}
            <div class="navbar-right">
                <div class="switch-locale text-right">
                    @foreach(allSupportedLocaleCodes() as $localeCode)
                        <a class="btn {{ $localeCode == $site_locale ? 'btn-success' : 'btn-default' }}" href="{{ currentUrl($localeCode) }}">
                            {{ $localeCode }}
                        </a>
                    @endforeach
                </div>
                @if($is_auth)
                    <div class="user-action margin-top-10 margin-bottom-10 clearfix">
                        <div class="dropdown pull-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img class="img-circle width-40" src="{{ $auth_user->url_avatar_thumb }}">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                @if($auth_user->can('access-admin'))
                                    <li><a href="{{ adminUrl() }}">{{ trans('form.action_go_to') }} {{ trans('pages.admin_title') }}</a></li>
                                @endif
                                @if($auth_user->hasRole(['teacher']))
                                    <li><a href="{{ homeUrl('teachers/{id}', ['id' => $auth_user->id]) }}">{{ trans('label.my_public_profile') }}</a></li>
                                @endif
                                <li><a href="{{ homeUrl('profile/account-information') }}">{{ trans('label.my_profile') }}</a></li>
                                @if($auth_user->hasRole(['teacher', 'student', 'supporter']))
                                    <li><a href="{{ homeUrl('opening-classrooms') }}">{{ trans('label.my_classrooms') }}</a></li>
                                @endif
                                @if($auth_user->can('create-articles'))
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ homeUrl('knowledge/articles/create') }}">
                                            {{ trans('form.action_create') }} {{ trans_choice('label.article_lc', 1) }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ homeUrl('knowledge/authors/{id}', ['id' => $auth_user->id]) }}">
                                            {{ trans('label.my_article') }}
                                        </a>
                                    </li>
                                @endif
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ homeUrl('auth/logout') }}">{{ trans('form.action_logout') }}</a></li>
                            </ul>
                        </div>
                        <?php $count_unread_announcements = $auth_user->countUnreadAnnouncements; ?>
                        <a href="{{ homeUrl('announcements') }}"
                           class="box-email box-40 box-circle {{ $count_unread_announcements == 0 ? 'bg-master' : 'bg-slave' }} box-center color-white pull-right margin-right-10">
                            <span class="bold-700">
                                <span{!! $count_unread_announcements == 0 ? ' style="display: none"' : '' !!}>{{ $count_unread_announcements }}</span>
                                <i class="fa fa-envelope-o"{!! $count_unread_announcements > 0 ? ' style="display: none"' : '' !!}></i>
                            </span>
                        </a>
                    </div>
                @else
                    <div class="login-action">
                        <a class="btn btn-primary btn-block uppercase bold-700"
                           href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
                    </div>
                    <div class="sign-up-action text-center">
                        <a href="{{ homeUrl('user/sign-up') }}">{!! trans('label.or_sign_up_here') !!}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>