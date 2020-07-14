<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @yield('site_meta')
    <title>{!! themeTitle() !!}</title>
    <meta name="description" content="{!! themeDescription() !!}">
    <meta name="keywords" content="{!! themeKeywords() !!}">
    <meta name="author" content="{!! themeAuthor() !!}">
    <meta name="application-name" content="{!! themeApplicationName() !!}">
    @include('fav_icons')
    <link rel="stylesheet" href="{{ _kExternalLink('bootstrap-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('katniss.conversation.css') }}">
    <!--[if lt IE 9]>
    <script src="{{ _kExternalLink('html5shiv') }}"></script>
    <script src="{{ _kExternalLink('respond') }}"></script>
    <![endif]-->
</head>
<body>
<header>
    <div class="wrapper">
        <div id="user-boxes">
            <div class="scrolling-box">
                <div class="users clearfix">
                    @foreach($conversation_users as $user)
                        <div class="square-box user" style="background-image:url({{ $user->url_avatar_thumb }})"
                             title="{{ $is_auth && $auth_user->id == $user->id ? trans('label.you') :  $user->display_name }}"
                             data-toggle="tooltip" data-placement="bottom"></div>
                    @endforeach
                    @foreach($conversation_devices as $device)
                        <div class="square-box device" style="background-color:#{{ $device->pivot->color }}"
                             title="{{ $device->uuid == deviceId() ? trans('label.you') : trans('label.anonymous') . '-' . $device->id }}"
                             data-toggle="tooltip" data-placement="bottom"></div>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-default btn-block no-border no-border-radius no-focus-outline
            scrolling-control pull-left hide">&laquo;</button>
            <button class="btn btn-default btn-block no-border no-border-radius no-focus-outline
            scrolling-control pull-right hide">&raquo;</button>
        </div>
        <hr>
    </div>
</header>
<main>
    <div class="wrapper">
        <div id="messages">
            <div id="clearfix-0" class="clearfix"></div>
        </div>
    </div>
</main>
<footer>
    <div class="wrapper">
        <hr>
        <div id="message">
                <textarea id="inputMessage" class="form-control no-border no-border-radius no-resize no-focus-outline"
                          name="inputMessage" placeholder="{{ trans_choice('label.message', 1) }}" rows="1" cols="10"
                          maxlength="255" required></textarea>
        </div>
        <div id="actions">
            <label for="inputEnter" class="sr-only">{{ trans_choice('label.message', 1) }}</label>
            <input id="inputEnter" name="inputEnter" type="checkbox" title="{{ trans('label.function_enter_to_send') }}" data-toggle="tooltip" data-placement="top">
            <button type="button" id="buttonSend" data-action-send="{{ trans('form.action_send') }}" data-action-press-enter="{{ trans('form.action_press_enter') }}"
                    class="btn btn-default btn-block no-border no-border-radius no-focus-outline">
                {{ trans('form.action_send') }}
            </button>
        </div>
        <div class="clearfix"></div>
    </div>
</footer>
<script src="{{ _kExternalLink('jquery') }}"></script>
<script src="{{ _kExternalLink('bootstrap') }}"></script>
<script src="{{ _kExternalLink('realtime-co-ortc') }}"></script>
{!! extScripts() !!}
{!! $js_queue !!}
<script src="{{ libraryAsset('katniss.conversation.js') }}"></script>
</body>
</html>