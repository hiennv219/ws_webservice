@section('extended_scripts')
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="margin-bottom">
            <a class="btn btn-primary" href="{{ addExtraUrl('admin/polls/create', adminUrl('extra')) }}">
                {{ trans('form.action_add') }} {{ trans_choice('polls.poll_lc', 1) }}
            </a>
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('polls.poll', 2)]) }}</h3>
            </div><!-- /.box-header -->
            @if($polls->count()>0)
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans('label.description') }}</th>
                            <th>{{ trans_choice('polls.choice', 2) }}</th>
                            <th>{{ trans('polls.multi_choice') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans('label.description') }}</th>
                            <th>{{ trans_choice('polls.choice', 2) }}</th>
                            <th>{{ trans('polls.multi_choice') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($polls as $poll)
                            <tr>
                                <td class="order-col-1">{{ ++$start_order }}</td>
                                <td>{{ $poll->name }}</td>
                                <td>{{ $poll->description }}</td>
                                <td>{{ $poll->choices()->count() }}</td>
                                <td>
                                    @if($poll->multi_choice)
                                        <label class="label label-success">{{ trans('label.status_enabled') }}</label>
                                    @else
                                        <label class="label label-default">{{ trans('label.status_disabled') }}</label>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ addExtraUrl('admin/polls/id/edit', adminUrl('extra')) . '&id=' . $poll->id }}">
                                        {{ trans('form.action_edit') }}
                                    </a>
                                    <a href="{{ addExtraUrl('admin/polls/id/sort', adminUrl('extra')) . '&id=' . $poll->id }}">
                                        {{ trans('form.action_sort') }}
                                    </a>
                                    <a class="delete"
                                       href="{{ addRdrUrl(addExtraUrl('admin/polls/id', adminUrl('extra')) . '&id=' . $poll->id) }}">
                                        {{ trans('form.action_delete') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    {{ $pagination }}
                </div>
            @else
                <div class="box-body">
                    {{ trans('label.list_empty') }}
                </div>
            @endif
        </div><!-- /.box -->
    </div>
</div>