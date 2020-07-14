<header class="main-header">
    <a href="{{ homeUrl() }}" class="logo">
        <span class="logo-mini">{{ $site_short_name }}</span>
        <span class="logo-lg">{{ $site_name }}</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ $auth_user->url_avatar_thumb }}" class="user-image" alt="{{ $auth_user->display_name }}">
                        <span class="hidden-xs">{{ $auth_user->display_name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ $auth_user->url_avatar_thumb }}" class="img-circle" alt="{{ $auth_user->display_name }}">
                            <p>
                                {{ $auth_user->display_name }}
                                <small>{{ trans('label._member_since', ['time' => $auth_user->memberSince]) }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ meUrl('account') }}" class="btn btn-default btn-flat">
                                    {{ trans('pages.my_account_title') }}
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ homeUrl('auth/logout') }}" class="btn btn-default btn-flat">
                                    {{ trans('form.action_logout') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<aside class="main-sidebar">
    <section class="sidebar">
        {{ $admin_menu }}
    </section>
</aside>