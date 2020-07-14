@if($links->count()>0)
    <div id="home-introduce-features-2" class="padding-v-20 margin-bottom-20">
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
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-left">
                                    <div class="box-40">
                                        <img class="box-40" src="{{ $link->image }}">
                                    </div>
                                </div>
                                <div class="media-body text-center">
                                    <h5 class="margin-top-none">{{ $link->name }}</h5>
                                    <div>{{ $link->description }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif