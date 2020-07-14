@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-account-information',
    'panel_heading' => trans('label.account_information'),
])
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('cropperjs/cropper.min.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('cropperjs/cropper.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            new CropImageModal($('body'), 1, 'user/{{ $auth_user->id }}/avatar/cropper-js');
            $('#change-password-form').on('submit', function (e) {
                e.preventDefault();

                var $this = $(this);
                var $submit = $this.find('[type="submit"]');
                var $alert = $this.find('.alert');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.put('{{ webApiUrl('me/account/password') }}', {
                    password: $this.find('input[name="password"]').val(),
                    password_confirmation: $this.find('input[name="password_confirmation"]').val(),
                    current_password: $this.find('input[name="current_password"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $('#change-password-modal').modal('hide');
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.change_password_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                });
            });
            $('#submitSkypeId').on('click', function (e) {
                e.preventDefault();

                var $alert = $(this).closest('.media').prev('.alert');
                $alert.removeClass('alert-success').addClass('alert-danger hide');
                var api = new KatnissApi(true);
                api.put('{{ webApiUrl('me/account/skype-id') }}', {
                    skype_id: $('#inputSkypeId').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $alert.addClass('alert-success').removeClass('alert-danger hide').html('{{ trans('error.success') }}');
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.change_password_failed') }}');
                });
            });
            $('#submitConnectFacebook').on('click', function (e) {
                e.preventDefault();

                FB.login(function () {
                    FB.api('/me', {
                        fields: 'id,name,email,picture.type(large)'
                    }, function (response) {
                        if (response.error) {
                            x_modal_alert('{{ trans('error.fail') }}');
                            return;
                        }
                        var api = new KatnissApi(true);
                        api.post('me/account/connect-facebook', {
                            id: response.id,
                            avatar: response.picture.data.url
                        }, function (failed, data, messages) {
                            if (failed) {
                                x_modal_alert('{{ trans('error.fail') }}');
                            }
                            else {
                                window.location.reload(true);
                            }
                        });
                    });
                });
            });
            $('#submitDisconnectFacebook').on('click', function (e) {
                e.preventDefault();

                var api = new KatnissApi(true);
                api.post('me/account/disconnect-facebook', {}, function (failed, data, messages) {
                    if (failed) {
                        x_modal_alert('{{ trans('error.fail') }}');
                    }
                    else {
                        window.location.reload(true);
                    }
                });
            });
        });
    </script>
@endsection
@section('modals')
    @include('modal_cropper_image')
    <div class="modal fade modal-success" id="change-password-modal" tabindex="-1" role="dialog"
         aria-labelledby="change-password-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="change-password-modal-title">
                        {{ trans('form.action_change') }} {{ trans('label.password') }}
                    </h4>
                </div>
                <form id="change-password-form" method="post" action="{{ webApiUrl('me/account/password') }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div id="change-password-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputPassword">{{ trans('label.password') }}:</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="inputPasswordRetype">{{ trans('label.password_retype') }}:</label>
                            <input type="password" class="form-control" id="inputPasswordRetype" name="password_confirmation" placeholder="{{ trans('label.password_retype') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputCurrentPassword">{{ trans('label.current_password') }}:</label>
                            <input type="password" class="form-control" id="inputCurrentPassword" name="current_password" placeholder="{{ trans('label.current_password') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ trans('form.action_cancel') }}
                        </button>
                        <button id="change-password-modal-button" type="submit" class="btn btn-success">
                            {{ trans('form.action_change') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('profile_content')
    <div class="form-group">
        <div class="row img-edit-wrapper">
            <div class="col-sm-4 text-center">
                <img id="profile-user-img" class="profile-user-img full-width" src="{{ $auth_user->url_avatar }}" alt="{{ $auth_user->display_name }}">
            </div>
            <div class="col-sm-8">
                <p>{{ trans('label.profile_account_avatar_help') }}</p>
                <button type="button" class="btn btn-success cropper-image-view" data-img="#profile-user-img">{{ trans('form.action_upload_avatar') }}</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6">
            <i class="fa fa-envelope-o"></i> &nbsp;
            {{ $auth_user->email }}
        </div>
        <div class="col-sm-6">
            <hr class="visible-xs">
            <a href="#" data-toggle="modal" data-target="#change-password-modal">
                <i class="fa fa-lock"></i> &nbsp;
                {{ trans('form.action_change') }} {{ trans('label.password') }}
            </a>
        </div>
    </div>
    <hr>
    <div class="alert alert-danger hide"></div>
    <div class="media">
        <div class="media-left">
            <img class="width-35" src="{{ themeImageAsset('skype.png') }}" alt="Skype">
        </div>
        <div class="media-body">
            <div class="input-group">
                <input type="text" placeholder="Skype ID" value="{{ $auth_user->skype_id }}"
                       class="form-control" id="inputSkypeId" name="skype_id">
                <span class="input-group-btn">
                    <button id="submitSkypeId" type="button" class="btn btn-primary">{{ trans('form.action_save') }}</button>
                </span>
            </div>
        </div>
    </div>
    @if($social_integration && $social_integration->facebook_enable)
        <div class="alert alert-danger hide"></div>
        <div class="media">
            <div class="media-left">
                <img class="width-35" src="{{ themeImageAsset('facebook.png') }}" alt="Facebook">
            </div>
            <div class="media-body">
                <div class="input-group">
                    <input type="text" placeholder="{{ trans('form.action_connect') }} Facebook"
                           value="{{ $has_facebook_connected ? trans('label.status_connected') : trans('label.status_not_connected') }}"
                           class="form-control" id="inputFacebookConnect" name="facebook_connect" readonly>
                    <span class="input-group-btn">
                        @if(!$has_facebook_connected)
                            <button id="submitConnectFacebook" type="button" class="btn btn-primary">{{ trans('form.action_connect') }}</button>
                        @else
                            <button id="submitDisconnectFacebook" type="button" class="btn btn-primary">{{ trans('form.action_disconnect') }}</button>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    @endif
@endsection