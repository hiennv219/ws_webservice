@section('extended_scripts')
    <script>
        $(function () {
            $('.sortable').sortable({
                placeholder: 'sort-highlight',
                handle: '.handle',
                forcePlaceholderSize: true,
                zIndex: 999999,
                update: function (e, ui) {
                    var items = [];
                    var self = $(this);
                    self.children().each(function () {
                        items.push($(this).attr('data-item'));
                    });
                    var api = new KatnissApi(true);
                    var params = {
                        id: '{{ $poll->id }}',
                        choice_ids: items,
                        sort: 1
                    };
                    params[KATNISS_EXTRA_ROUTE_PARAM] = 'web-api/polls/id';
                    api.put('extra', params);
                }
            });

            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-md-6">
            <div class="margin-bottom">
                <a role="button" class="btn btn-warning delete"
                   href="{{ addErrorUrl(addRdrUrl(addExtraUrl('admin/polls/id', adminUrl('extra')) . '&id=' . $poll->id, addExtraUrl('admin/polls', adminUrl('extra')))) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a role="button" class="btn btn-primary"
                   href="{{ addExtraUrl('admin/polls/id/edit', adminUrl('extra')) . '&id=' . $poll->id }}">
                    {{ trans('form.action_edit') }}
                </a>
                <a role="button" class="btn btn-primary pull-right"
                   href="{{ addExtraUrl('admin/polls/create', adminUrl('extra')) }}">
                    {{ trans('form.action_add') }} {{ trans_choice('polls.poll_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.action_sort') }} {{ trans_choice('polls.poll_lc', 1) }} - <em>{{ $poll->name }}</em></h3>
                </div>
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if($choices->count()>0)
                        <ul class="todo-list sortable" data-poll="{{ $poll->id }}">
                            @foreach($choices as $choice)
                                <li data-item="{{ $choice->id }}">
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">{{ $choice->name }} ({{ trans('polls.votes') }}: {{ $choice->votes }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ trans('label.list_empty') }}</p>
                    @endif
                </div>
            </div>
            <div class="pull-right">
                <a role="button" class="btn btn-warning" href="{{ addExtraUrl('admin/polls', adminUrl('extra')) }}">{{ trans('form.action_cancel') }}</a>
            </div>
        </div>
    </div>
@endsection