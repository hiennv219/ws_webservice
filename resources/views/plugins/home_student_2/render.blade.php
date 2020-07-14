@if($has_video_1 || $has_video_2)
    <div id="home-student-2" class="margin-bottom-20 padding-20 bg-master-lighter">
        @if(!empty($name))
            <div class="text-center">
                <h4 class="uppercase bold-700 color-white">{{ $name }}</h4>
                @if(!empty($description))
                    <p class="lead text-muted">{{ $description }}</p>
                @endif
            </div>
            <div>&nbsp;</div>
        @endif
        <div class="row">
            @if($has_video_1)
                <div class="{{ $has_video_2 ? 'col-sm-6' : 'col-sm-12' }}">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ $video_url_1 }}" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
            @if($has_video_1 && $has_video_2)
                <div class="visible-xs">&nbsp;</div>
            @endif
            @if($has_video_2)
                <div class="{{ $has_video_1 ? 'col-sm-6' : 'col-sm-12' }}">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ $video_url_2 }}" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif