@if(count($teachers) > 0)
    <div id="home-teacher" class="padding-top-10 margin-bottom-20">
        @if(!empty($name))
            <div class="text-center">
                <h4 class="uppercase bold-700">{{ $name }}</h4>
                @if(!empty($description))
                    <p class="lead text-muted">{{ $description }}</p>
                @endif
            </div>
            <div class="margin-v-10">&nbsp;</div>
        @endif
        @foreach(array_chunk($teachers, 2) as $chunked_teachers)
            <div class="row">
            	@foreach($chunked_teachers as $teacher)
	                <div class="col-sm-6 teacher-item">
	                    <div class="media">
	                        <div class="media-left">
	                            <a target="_blank" href="{{ $teacher['url'] }}">
	                                <div class="teacher-avatar box-150">
	                                    <img class="box-150 thumbnail padding-none border-master border-2x img-responsive"
	                                         src="{{ $teacher['avatar'] }}"
	                                         alt="{{ $teacher['display_name'] }}">
	                                </div>
	                                <div class="teacher-meta padding-h-10">
	                                    <h5 class="bold-700 uppercase margin-bottom-5 color-white">{{ $teacher['display_name'] }}</h5>
	                                    <div class="color-slave">{{ $teacher['nationality'] }}</div>
	                                </div>
	                            </a>
	                        </div>
	                        <div class="media-body">
	                            <div class="text-justify margin-bottom-5 teacher-review">
	                                {{ $teacher['review'] }}
	                                &nbsp;<img src="{{ ThemeFacade::imageAsset('icon_quote.png') }}">
	                            </div>
	                            <div class="text-right bold-700 margin-bottom-5"><em>{{ $teacher['tag_line'] }}</em></div>
	                            <div class="text-right">
	                                <a class="btn btn-primary" href="{{ $teacher['url'] }}">
	                                    <em>{{ trans('form.action_see_more') }}</em>
	                                </a>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	        @endforeach
	    </div>
        @endforeach
        <div class="text-center margin-top-10">
            <a class="btn btn-primary btn-lg uppercase bold-700" href="{{ homeUrl('student/sign-up') }}">
                {{ trans('form.action_register_class') }}
            </a>
        </div>
    </div>
@endif