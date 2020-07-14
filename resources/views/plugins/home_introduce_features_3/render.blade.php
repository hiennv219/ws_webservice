@if($links->count()>0)
    <div id="home-introduce-features-3" class="padding-v-20 padding-h-30 margin-bottom-20 bg-master color-white">
        @if(!empty($name))
            <div class="text-center margin-v-10">
                <h4 class="uppercase bold-700">{{ $name }}</h4>
                @if(!empty($description))
                    <p class="lead text-muted">{{ $description }}</p>
                @endif
            </div>
            <div class="margin-v-15">&nbsp;</div>
        @endif
        <div class="row">
            @foreach($links as $link)
                <div class="col-sm-4">
                    <div class="box-100 box-center box-circle align-center">
                        <span><img class="box-100 img-circle border-solid border-10x border-white-1-2" src="{{ $link->image }}"></span>
                    </div>
                    <h4 class="text-center bold-600 margin-bottom-15 margin-top-25">{{ $link->name }}</h4>
                    <div class="text-center">{{ $link->description }}</div>
                </div>
            @endforeach
        </div>
        <div class="text-center margin-v-20">
            <a class="btn btn-primary-inverse btn-lg uppercase bold-700" href="{{ homeUrl('student/sign-up') }}">
                {{ trans('form.action_register_class') }}
            </a>
        </div>
    </div>
@endif