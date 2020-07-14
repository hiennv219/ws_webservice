<h4 class="uppercase margin-bottom-15 margin-top-none bold-700 color-master">
    {{ trans('register_help.become_student') }}
</h4>
<ul class="media-list step-list margin-bottom-none">
    <li class="media">
        <div class="media-line"></div>
        <div class="media-left">
            <span><img src="{{ ThemeFacade::imageAsset('icon_stats.png') }}"></span>
        </div>
        <div class="media-body">
            <div class="color-master">{{ trans('register_help.become_student_step_1') }}</div>
        </div>
    </li>
    <li class="media">
        <div class="media-line"></div>
        <div class="media-left">
            <span><img src="{{ ThemeFacade::imageAsset('icon_cogs.png') }}"></span>
        </div>
        <div class="media-body">
            <div class="color-master">{{ trans('register_help.become_student_step_2') }}</div>
        </div>
    </li>
    <li class="media">
        <div class="media-line"></div>
        <div class="media-left">
            <span><img src="{{ ThemeFacade::imageAsset('icon_search.png') }}"></span>
        </div>
        <div class="media-body">
            <div class="color-master">{{ trans('register_help.become_student_step_3') }}</div>
        </div>
    </li>
    <li class="media">
        <div class="media-line"></div>
        <div class="media-left">
            <span><img src="{{ ThemeFacade::imageAsset('icon_trial_class.png') }}"></span>
        </div>
        <div class="media-body">
            <div class="color-master">{{ trans('register_help.become_student_step_5') }}</div>
        </div>
    </li>
    <li class="media">
        <div class="media-line"></div>
        <div class="media-left">
            <span><img src="{{ ThemeFacade::imageAsset('icon_coin.png') }}"></span>
        </div>
        <div class="media-body">
            <div class="color-master">{{ trans('register_help.become_student_step_4') }}</div>
        </div>
    </li>
    <li class="media">
        <div class="media-line"></div>
        <div class="media-left">
            <span><img src="{{ ThemeFacade::imageAsset('icon_pie.png') }}"></span>
        </div>
        <div class="media-body">
            <div class="color-master">{{ trans('register_help.become_student_step_6') }}</div>
        </div>
    </li>
    <li class="media">
        <div class="media-body">
            <a role="button" class="btn btn-primary btn-block uppercase bold-700"
               href="{{ homeUrl('student/sign-up') }}">
                {{ trans('form.action_register_class') }}
            </a>
        </div>
    </li>
</ul>