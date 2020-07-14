<div class="modal fade modal-primary" id="x-confirm-modal" tabindex="-1" role="dialog"
     aria-labelledby="x-confirm-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="x-confirm-modal-title"></h4>
            </div>
            <div id="x-confirm-modal-content" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">
                    {{ trans('form.action_cancel') }}
                </button>
                <button id="x-confirm-modal-button" type="button" class="btn btn-success" data-dismiss="modal">
                    {{ trans('form.action_confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-danger" id="x-alert-modal" tabindex="-1" role="dialog"
     aria-labelledby="x-alert-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="x-alert-modal-title">{{ trans('label.alert') }}</h4>
            </div>
            <div id="x-alert-modal-content" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">
                    {{ trans('form.action_close') }}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade lockscreen" id="x-lock-modal" tabindex="-1" role="dialog" aria-labelledby="x-lock-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content no-shadow bg-transparent">
            <div id="x-lock-modal-content" class="modal-body">
                <div class="lockscreen-wrapper">
                    <div class="lockscreen-logo text-center">
                        <a href="{{ homeUrl() }}"><strong>{{ $site_name }}</strong></a>
                    </div>
                    <div class="lockscreen-name text-center">{{ isAuth() ? $auth_user->display_name : '' }}</div>
                    <div class="lockscreen-item">
                        <div class="lockscreen-image text-center">
                            <img src="{{ isAuth() ? $auth_user->url_avatar : asset('avatar.png') }}" alt="User Image">
                        </div>
                        <form class="lockscreen-credentials" method="post" action="{{ apiUrl('user/quick-login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{ isAuth() ? $auth_user->id : '' }}">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control"
                                       placeholder="{{ trans('label.password') }}">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn">
                                        <i class="fa fa-arrow-right text-muted"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="help-block text-center">
                        {{ trans('label.enter_password_for_session') }}
                    </div>
                    <div class="text-center">
                        <a href="{{ homeUrl('auth/login') }}">{{ trans('label.or_different_sign_in') }}</a>
                    </div>
                    <div class="lockscreen-footer text-center">
                        <strong>
                            {{ trans('label.copyright') }} &copy; {{ date('Y') }}
                            <a href="{{ $site_home_url }}">{{ $site_name }} {{ $site_version }}</a>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function x_modal_confirm(title, content, callback) {
        var confirmModal = $('#x-confirm-modal');
        confirmModal.find('#x-confirm-modal-title').html(title);
        confirmModal.find('#x-confirm-modal-content').html(content);
        confirmModal.find('#x-confirm-modal-button').on('click', function () {
            if (typeof callback === 'function') {
                callback.call();
            }
        });
        confirmModal.modal('show').on('hide.bs.modal', function () {
            confirmModal.find('#x-confirm-modal-title').text('');
            confirmModal.find('#x-confirm-modal-content').text('');
            confirmModal.find('#x-confirm-modal-button').off('click');

            confirmModal.off('hide.bs.modal');
        });
    }

    function x_modal_location(title, content, url) {
        x_modal_confirm(title, content, function () {
            window.location.href = url;
        });
    }

    function x_modal_url($element, title, content) {
        $element.off('click').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            x_modal_location(title, content, $this.is('a') ? $this.attr('href') : $this.attr('data-url'))
        });
    }

    function x_modal_form(title, content, action, method) {
        var data = {
            _token: KATNISS_REQUEST_TOKEN
        };
        if (method != 'post' && method != 'get') {
            data._method = method;
        }
        if (method != 'get') method = 'post';
        x_modal_confirm(title, content, function () {
            quickForm(action, data, method).submit();
        });
    }

    function x_modal_delete($element, title, content) {
        $element.off('click').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            x_modal_form(title, content, $this.is('a') ? $this.attr('href') : $this.attr('data-delete'), 'delete');
        });
    }

    function x_modal_put($element, title, content) {
        $element.off('click').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            x_modal_form(title, content, $this.is('a') ? $this.attr('href') : $this.attr('data-put'), 'put');
        });
    }

    function x_modal_alert(content) {
        var $alertModal = $('#x-alert-modal');
        $alertModal.find('#x-alert-modal-content').html(content);
        $alertModal.modal('show');
    }

    function x_modal_lock() {
        var $lockModal = $('#x-lock-modal');
        $lockModal.modal({backdrop: 'static'}).modal('show');
    }

    $(function () {
        var $lockId = $('.lockscreen-credentials input[name="id"]');
        var $lockPassword = $('.lockscreen-credentials input[name="password"]');
        var $lockImage = $('.lockscreen-image > img');
        $('.lockscreen-credentials').on('submit', function (e) {
            e.preventDefault();
            var api = new KatnissApi(true);
            api.get('user/quick-login', {
                id: $lockId.val(),
                password: $lockPassword.val()
            }, function (failed, data, messages) {
                if (!failed) {
                    KATNISS_USER = data.user;
                    updateCsrfToken(data.csrf_token);
                    $lockId.val(KATNISS_USER.id);
                    $lockPassword.val('');
                    $lockImage.attr('src', KATNISS_USER.url_avatar);
                    $('#x-lock-modal').modal('hide');
                }
                else {
                    x_modal_alert(messages.first());
                }
            });
            return false;
        });
    });
</script>