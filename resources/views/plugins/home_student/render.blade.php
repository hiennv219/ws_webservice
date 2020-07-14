@if($has_video || $has_reviews)
    <div id="home-student" class="margin-bottom-20 padding-20 bg-master-lighter">
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
            @if($has_video)
                <div class="{{ $has_reviews ? 'col-sm-6' : 'col-sm-12' }}">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ $video_url }}" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
            @if($has_reviews)
                <div class="{{ $has_video ? 'col-sm-6' : 'col-sm-12' }}">
                    <div class="student-review-list">
                        <?php $i = 0; ?>
                        @foreach($reviews as $review)
                            <div class="student-review-item border-solid border-2x border-master bg-white {{ ++$i % 2 == 1 ? 'right' : 'left' }}">
                                <div class="text-justify small">"{{ $review['review'] }}"</div>
                                <img class="box-60 box-circle border-solid border-2x border-master" src="{{ $review['picture'] }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif