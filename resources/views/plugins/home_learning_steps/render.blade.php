@if($links->count()>0)
    <div id="home-learning-steps" class="padding-v-10 margin-bottom-20">
        @if(!empty($name))
            <div class="text-center">
                <h4 class="uppercase bold-700">{{ $name }}</h4>
                @if(!empty($description))
                    <p class="lead text-muted">{{ $description }}</p>
                @endif
            </div>
            <div class="margin-v-10">&nbsp;</div>
        @endif
        @while($links->count()>0 && $splice_items = $links->splice(0, 6))
            <div class="row margin-h-none">
                <?php
                $step = 0;
                $count_items = $splice_items->count();
                ?>
                @if($count_items < 6)
                    <div class="col-xs-12 col-sm-6 col-md-{{ (12-2*$count_items)/2 }} hidden-sm padding-h-none">
                    </div>
                @endif
                <?php
                $first_id = $splice_items->first()->id;
                $last_id = $splice_items->last()->id;
                ?>
                @foreach($splice_items as $item)
                    <div class="step-col {{ $item->id == $first_id ? 'step-col-first' : ($item->id == $last_id ? 'step-col-last' : '') }} col-xs-12 col-sm-6 col-md-2 text-center margin-v-10 padding-h-none">
                        <div class="step-image-box box-60 box-center box-circle align-center border-solid border-4x border-master bg-white">
                            <span><img class="width-40 height-40" src="{{ $item->image }}"></span>
                        </div>
                        <div class="step-dash clearfix">
                            <hr class="margin-none border-3x border-master wp-2 pull-left">
                            <hr class="margin-none border-3x border-master wp-2 pull-right">
                        </div>
                        <p class="margin-top-10 padding-h-5 color-master">{{ $item->name }}</p>
                    </div>
                @endforeach
                @if($count_items < 6)
                    <div class="col-xs-12 col-sm-6 col-md-{{ (12-2*$count_items)/2 }} hidden-sm padding-h-none">
                    </div>
                @endif
            </div>
        @endwhile
        <div class="text-center margin-bottom-10">
            <a class="btn btn-primary btn-lg uppercase bold-700" href="{{ homeUrl('student/sign-up') }}">
                {{ trans('form.action_register_class') }}
            </a>
        </div>
    </div>
@endif