@if($links->count()>0)
    <div id="home-introduce-features" class="padding-v-20 margin-bottom-20 bg-master-lighter">
        @if(!empty($name))
            <div class="text-center margin-v-20">
                <h4 class="uppercase bold-700">{{ $name }}</h4>
                @if(!empty($description))
                    <p class="lead text-muted">{{ $description }}</p>
                @endif
            </div>
            <div class="margin-v-15">&nbsp;</div>
        @endif
        <div class="row">
            @foreach($links as $link)
                <div class="col-sm-3">
                    <div class="box-100 box-center box-circle align-center bg-white">
                        <span><img class="box-100" src="{{ $link->image }}"></span>
                    </div>
                    <h5 class="text-center uppercase bold-700 margin-v-20">{{ $link->name }}</h5>
                </div>
            @endforeach
        </div>
    </div>
@endif