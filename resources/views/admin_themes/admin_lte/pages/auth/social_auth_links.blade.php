<div class="social-auth-links text-center">
    @if($social_integration && $social_integration->social_login_enable)
        <p class="text-uppercase">- {{ trans('label.or') }} -</p>
        @if($social_integration->facebook_login_enable)
            <a href="{{ homeUrl('auth/social/{provider}', array('provider' => 'facebook')) }}" class="btn btn-block btn-social btn-facebook btn-flat">
                <i class="fa fa-facebook"></i> {{ trans('label.sign_in_with_facebook') }}
            </a>
        @endif
        @if($social_integration->google_login_enable)
            <a href="{{ homeUrl('auth/social/{provider}', array('provider' => 'google')) }}" class="btn btn-block btn-social btn-google btn-flat">
                <i class="fa fa-google-plus"></i> {{ trans('label.sign_in_with_google') }}
            </a>
        @endif
    @endif
</div>