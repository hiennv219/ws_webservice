@if($has_media || $has_text)
    <div id="home-cover" class="margin-bottom-20 clearfix">
        @if($has_media)
            <div class="cover-media" style="width:{{ !$has_text ? '100' : '75' }}%">
                <a class="cover-media-video" target="_blank" href="{{ $video_url }}">
                    <div class="embed-responsive embed-responsive-16by9">
                        <img class="embed-responsive-item" src="{{ $image }}">
                        <div class="cover-media-play-table">
                            <div class="cover-media-play-cell">
                                <img class="width-100 height-100" src="{{ ThemeFacade::imageAsset('icon_video_play_master_trans.png') }}">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @elseif($has_image)
            <div class="cover-media" style="width:{{ !$has_text ? '100' : '75' }}%">
                <div class="embed-responsive embed-responsive-16by9">
                    <img class="embed-responsive-item" src="{{ $image }}">
                </div>
            </div>
        @elseif($has_video)
            <div class="cover-media" style="width:{{ !$has_text ? '100' : '75' }}%">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="{{ $video_url }}"></iframe>
                </div>
            </div>
        @endif
        @if($has_text)
            <div class="cover-text padding-right-20" style="width:{{ $no_media ? '100' : '25' }}%">
                @if(!empty($content))
                    <p class="text-justify">{{ $content }}</p>
                @endif
                @if(!empty($link))
                    <div class="text-right">
                        <a href="{{ $link }}" class="btn btn-primary uppercase bold-700">
                            {{ trans('form.action_see_more') }}
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endif