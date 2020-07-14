@if($pages->count()>0)
    <div id="{{ $html_id }}" class="widget-pages">
        <div class="panel panel-default" data-toggle="panel-collapse" data-open="true">
            @if(!empty($name))
                <div class="panel-heading panel-collapse-trigger">
                    <h4 class="panel-title">{{ $name }}</h4>
                </div>
            @endif
            <div class="panel-body list-group">
                <ul class="list-group list-group-menu">
                    @foreach($pages as $page)
                        <li class="list-group-item">
                            <a href="{{ homeUrl('example/pages/{id}', ['id' => $page->id]) }}">
                                {{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif