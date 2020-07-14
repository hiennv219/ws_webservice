@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection

<form method="post" action="{{ currentFullUrl() }}">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="row">
        <div class="col-xs-12">
            <h4 class="box-title">{{ trans('form.action_edit') }}</h4>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                            <a href="#tab_{{ $locale }}" data-toggle="tab">
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab_{{ $locale }}">
                            <div class="form-group">
                                <label for="{{ localeInputId('inputHomeName', $locale) }}">{{ trans('wow_skype_theme.home_name') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputHomeName', $locale) }}"
                                       name="{{ localeInputName('home_name', $locale) }}" type="text"
                                       placeholder="{{ trans('wow_skype_theme.home_name') }}" value="{{ $home_theme->options('home_name', '', $locale) }}">
                            </div>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputHomeDescription', $locale) }}">{{ trans('wow_skype_theme.home_description') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputHomeDescription', $locale) }}"
                                       name="{{ localeInputName('home_description', $locale) }}" type="text"
                                       placeholder="{{ trans('wow_skype_theme.home_description') }}" value="{{ $home_theme->options('home_description', '', $locale) }}">
                            </div>
                            @if(isset($extended_localizing_path))
                                @include($extended_localizing_path)
                            @endif
                        </div><!-- /.tab-pane -->
                    @endforeach
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->

            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputSiteKeywords">{{ trans('wow_skype_theme.site_keywords') }}</label>
                        <input class="form-control" id="inputSiteKeywords" name="site_keywords"
                               placeholder="{{ trans('wow_skype_theme.site_keywords') }}" type="text" value="{{ $site_keywords }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputHomeEmail">{{ trans('wow_skype_theme.home_email') }}</label>
                        <input class="form-control" id="inputHomeEmail" name="home_email"
                               placeholder="{{ trans('wow_skype_theme.home_email') }}" type="text" value="{{ $home_email }}">
                    </div>
                    <div class="form-group">
                        <label for="inputHomeHotLine">{{ trans('wow_skype_theme.home_hot_line') }}</label>
                        <input class="form-control" id="inputHomeHotLine" name="home_hot_line"
                               placeholder="{{ trans('wow_skype_theme.home_hot_line') }}" type="text" value="{{ $home_hot_line }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputSocialFacebook">{{ trans('wow_skype_theme.social_facebook') }}</label>
                        <input class="form-control" id="inputSocialFacebook" name="social_facebook"
                               placeholder="{{ trans('wow_skype_theme.social_facebook') }}" type="text" value="{{ $social_facebook }}">
                        <div class="checkbox icheck">
                            <label for="inputSocialFacebookSf">
                                <input id="inputSocialFacebookSf" type="checkbox" name="social_facebook_sf"
                                       value="1"{{ $social_facebook_sf == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sf') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialFacebookSb">
                                <input id="inputSocialFacebookSb" type="checkbox" name="social_facebook_sb"
                                       value="1"{{ $social_facebook_sb == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sb') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialFacebookSw">
                                <input id="inputSocialFacebookSw" type="checkbox" name="social_facebook_sw"
                                       value="1"{{ $social_facebook_sw == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sw') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSocialTwitter">{{ trans('wow_skype_theme.social_twitter') }}</label>
                        <input class="form-control" id="inputSocialTwitter" name="social_twitter"
                               placeholder="{{ trans('wow_skype_theme.social_twitter') }}" type="text" value="{{ $social_twitter }}">
                        <div class="checkbox icheck">
                            <label for="inputSocialTwitterSf">
                                <input id="inputSocialTwitterSf" type="checkbox" name="social_twitter_sf"
                                       value="1"{{ $social_twitter_sf == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sf') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialTwitterSb">
                                <input id="inputSocialTwitterSb" type="checkbox" name="social_twitter_sb"
                                       value="1"{{ $social_twitter_sb == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sb') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialTwitterSw">
                                <input id="inputSocialTwitterSw" type="checkbox" name="social_twitter_sw"
                                       value="1"{{ $social_twitter_sw == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sw') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSocialInstagram">{{ trans('wow_skype_theme.social_instagram') }}</label>
                        <input class="form-control" id="inputSocialInstagram" name="social_instagram"
                               placeholder="{{ trans('wow_skype_theme.social_instagram') }}" type="text" value="{{ $social_instagram }}">
                        <div class="checkbox icheck">
                            <label for="inputSocialInstagramSf">
                                <input id="inputSocialInstagramSf" type="checkbox" name="social_instagram_sf"
                                       value="1"{{ $social_instagram_sf == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sf') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialInstagramSb">
                                <input id="inputSocialInstagramSb" type="checkbox" name="social_instagram_sb"
                                       value="1"{{ $social_instagram_sb == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sb') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialInstagramSw">
                                <input id="inputSocialInstagramSw" type="checkbox" name="social_instagram_sw"
                                       value="1"{{ $social_instagram_sw == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sw') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSocialGooglePlus">{{ trans('wow_skype_theme.social_gplus') }}</label>
                        <input class="form-control" id="inputSocialGooglePlus" name="social_gplus"
                               placeholder="{{ trans('wow_skype_theme.social_gplus') }}" type="text" value="{{ $social_gplus }}">
                        <div class="checkbox icheck">
                            <label for="inputSocialGooglePlusSf">
                                <input id="inputSocialGooglePlusSf" type="checkbox" name="social_gplus_sf"
                                       value="1"{{ $social_gplus_sf == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sf') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialGooglePlusSb">
                                <input id="inputSocialGooglePlusSb" type="checkbox" name="social_gplus_sb"
                                       value="1"{{ $social_gplus_sb == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sb') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialGooglePlusSw">
                                <input id="inputSocialGooglePlusSw" type="checkbox" name="social_gplus_sw"
                                       value="1"{{ $social_gplus_sw == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sw') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSocialYoutube">{{ trans('wow_skype_theme.social_youtube') }}</label>
                        <input class="form-control" id="inputSocialYoutube" name="social_youtube"
                               placeholder="{{ trans('wow_skype_theme.social_youtube') }}" type="text" value="{{ $social_youtube }}">
                        <div class="checkbox icheck">
                            <label for="inputSocialYoutubeSf">
                                <input id="inputSocialYoutubeSf" type="checkbox" name="social_youtube_sf"
                                       value="1"{{ $social_youtube_sf == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sf') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialYoutubeSb">
                                <input id="inputSocialYoutubeSb" type="checkbox" name="social_youtube_sb"
                                       value="1"{{ $social_youtube_sb == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sb') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialYoutubeSw">
                                <input id="inputSocialYoutubeSw" type="checkbox" name="social_youtube_sw"
                                       value="1"{{ $social_youtube_sw == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sw') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSocialSkype">{{ trans('wow_skype_theme.social_skype') }}</label>
                        <input class="form-control" id="inputSocialSkype" name="social_skype"
                               placeholder="{{ trans('wow_skype_theme.social_skype') }}" type="text" value="{{ $social_skype }}">
                        <div class="checkbox icheck">
                            <label for="inputSocialSkypeSf">
                                <input id="inputSocialSkypeSf" type="checkbox" name="social_skype_sf"
                                       value="1"{{ $social_skype_sf == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sf') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialSkypeSb">
                                <input id="inputSocialSkypeSb" type="checkbox" name="social_skype_sb"
                                       value="1"{{ $social_skype_sb == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sb') }}
                            </label>
                        </div>
                        <div class="checkbox icheck">
                            <label for="inputSocialSkypeSw">
                                <input id="inputSocialSkypeSw" type="checkbox" name="social_skype_sw"
                                       value="1"{{ $social_skype_sw == 1 ? ' checked' : '' }}>
                                &nbsp; {{ trans('wow_skype_theme.social_sw') }}
                            </label>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputTeacherSupportSkypeId">{{ trans('wow_skype_theme.ts_skype_id') }}</label>
                        <input class="form-control" id="inputTeacherSupportSkypeId" name="ts_skype_id"
                               placeholder="{{ trans('wow_skype_theme.ts_skype_id') }}" type="text" value="{{ $ts_skype_id }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTeacherSupportSkypeName">{{ trans('wow_skype_theme.ts_skype_name') }}</label>
                        <input class="form-control" id="inputTeacherSupportSkypeName" name="ts_skype_name"
                               placeholder="{{ trans('wow_skype_theme.ts_skype_name') }}" type="text" value="{{ $ts_skype_name }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTeacherSupportEmail">{{ trans('wow_skype_theme.ts_email') }}</label>
                        <input class="form-control" id="inputTeacherSupportEmail" name="ts_email"
                               placeholder="{{ trans('wow_skype_theme.ts_email') }}" type="text" value="{{ $ts_email }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTeacherSupportHotLine">{{ trans('wow_skype_theme.ts_hot_line') }}</label>
                        <input class="form-control" id="inputTeacherSupportHotLine" name="ts_hot_line"
                               placeholder="{{ trans('wow_skype_theme.ts_hot_line') }}" type="text" value="{{ $ts_hot_line }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputStudentSupportSkypeId">{{ trans('wow_skype_theme.ss_skype_id') }}</label>
                        <input class="form-control" id="inputStudentSupportSkypeId" name="ss_skype_id"
                               placeholder="{{ trans('wow_skype_theme.ss_skype_id') }}" type="text" value="{{ $ss_skype_id }}">
                    </div>
                    <div class="form-group">
                        <label for="inputStudentSupportSkypeName">{{ trans('wow_skype_theme.ss_skype_name') }}</label>
                        <input class="form-control" id="inputStudentSupportSkypeName" name="ss_skype_name"
                               placeholder="{{ trans('wow_skype_theme.ss_skype_name') }}" type="text" value="{{ $ss_skype_name }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputKnowledgeCoverImage">{{ trans('wow_skype_theme.knowledge_cover_image') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="inputKnowledgeCoverImage" name="knowledge_cover_image"
                                   placeholder="{{ trans('wow_skype_theme.knowledge_cover_image') }}" type="text" value="{{ $knowledge_cover_image }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="inputKnowledgeCoverImage">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputKnowledgeDefaultArticleImage">{{ trans('wow_skype_theme.knowledge_default_article_image') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="inputKnowledgeDefaultArticleImage" name="knowledge_default_article_image"
                                   placeholder="{{ trans('wow_skype_theme.knowledge_default_article_image') }}" type="text" value="{{ $knowledge_default_article_image }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="inputKnowledgeDefaultArticleImage">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="margin-top">
                <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                <div class="pull-right">
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</form>