@extends('home_themes.wow_skype.master.master')
@section('extended_scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            x_modal_put(
                $('.read'),
                '{{ trans('form.action_mark_as') }} {{ trans('label.status_read') }}',
                '{{ trans('label.wanna_mark_as', ['name' => trans('label.status_read')]) }}'
            );
        });
    </script>
@endsection
@section('main_content')
    <div id="page-announcements">
        <div class="classroom-heading clearfix">
            <div class="btn-group margin-top-20 pull-right">
                <a role="button" href="{{ $statuses[$current_status]['url'] }}" class="btn btn-default">
                    {!! $statuses[$current_status]['current'] !!}
                </a>
                <button type="button" class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    @foreach($statuses as $key => $status)
                        @if($key != $current_status)
                            <li><a href="{{ $status['url'] }}">{!! $status['label'] !!}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <h3 class="color-master"><strong>{{ trans_choice('label.announcement', 2) }}</strong></h3>
        </div>
        <hr>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @if($announcements->count() > 0)
            <div class="announcement-list">
                @foreach($announcements as $announcement)
                    <?php $is_read = in_array($announcement->id, $read_ids); ?>
                    <div id="announcement-{{ $announcement->id }}"
                         class="announcement-item{{ $is_read ? '' : ' unread' }}">
                        @if(!empty($announcement->title))
                            <h4 class="bold-700">{{ $announcement->title }}</h4>
                        @endif
                        <p class="small color-normal">&#8212; {{ $announcement->diffDays == 0 ? trans('datetime.today') : $announcement->diffDays .' '. trans_choice('label.days_ago_lc', $announcement->diffDays) }}</p>
                        {!! $announcement->htmlContent !!}
                        @if($is_read)
                            <button type="button" class="btn btn-primary box-40 box-circle"
                                    data-toggle="tooltip"
                                    title="{{ trans('label.status_read') }}">
                                <i class="fa fa-bullhorn"></i>
                            </button>
                        @else
                            <button type="button" class="read btn btn-success box-40 box-circle"
                                    data-toggle="tooltip"
                                    data-put="{{ addRdrUrl(homeUrl('announcements/{id}', ['id' => $announcement->id]) . '?read=1') }}"
                                    title="{{ trans('form.action_mark_as') }} {{ trans('label.status_read') }}">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="text-center">
                {{ $pagination }}
            </div>
        @else
            <div>
                {{ trans('label.list_empty') }}
            </div>
        @endif
    </div>
@endsection