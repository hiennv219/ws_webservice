<div class="bg-master padding-v-5 margin-top-10">
    <div class="wrapper bg-master clearfix">
        <ul class="bottom-social-links list-inline margin-bottom-none pull-left">
            @if(!empty($social_facebook))
                <li>
                    <a target="_blank" href="{{ $social_facebook }}">
                        <span class="color-white"><i class="fa fa-facebook-square"></i></span>
                    </a>
                </li>
            @endif
            @if(!empty($social_twitter))
                <li>
                    <a target="_blank" href="{{ $social_twitter }}">
                        <span class="color-white"><i class="fa fa-twitter-square"></i></span>
                    </a>
                </li>
            @endif
            @if(!empty($social_instagram))
                <li>
                    <a target="_blank" href="{{ $social_instagram }}">
                        <span class="color-white"><i class="fa fa-instagram"></i></span>
                    </a>
                </li>
            @endif
            @if(!empty($social_gplus))
                <li>
                    <a target="_blank" href="{{ $social_gplus }}">
                        <span class="color-white"><i class="fa fa-google-plus-square"></i></span>
                    </a>
                </li>
            @endif
            @if(!empty($social_youtube))
                <li>
                    <a target="_blank" href="{{ $social_youtube }}">
                        <span class="color-white"><i class="fa fa-youtube-square"></i></span>
                    </a>
                </li>
            @endif
            @if(!empty($social_skype))
                <li>
                    <a target="_blank" href="skype:{{ $social_skype }}?chat">
                        <span class="color-white"><i class="fa fa-skype"></i></span>
                    </a>
                </li>
            @endif
        </ul>
  
        @if(!empty($home_name))
            <div class="pull-right color-white small bold-700">
                {{ $home_name }} &copy; {{ date('Y') }}. All rights reserved.
            </div>
        @endif
    </div>
</div>