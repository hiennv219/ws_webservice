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
            <a class="btn btn-primary" href="{{ addExtraUrl('admin/poll-choices/create', adminUrl('extra')) }}">
                {{ trans('form.action_add') }} {{ trans_choice('polls.choice_lc', 1) }}
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
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('polls.choice', 2)]) }}</h3>
            </div><!-- /.box-header -->
            @if($choices->count()>0)
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans_choice('polls.poll', 1) }}</th>
                            <th>{{ trans('polls.votes') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans_choice('polls.poll', 1) }}</th>
                            <th>{{ trans('polls.votes') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($choices as $choice)
                            <tr>
                                <td class="order-col-1">{{ ++$start_order }}</td>
                                <td>{{ $choice->name }}</td>
                                <td>{{ $choice->poll->name }}</td>
                                <td>{{ $choice->votes }}</td>
                                <td>
                                    <a href="{{ addExtraUrl('admin/poll-choices/id/edit', adminUrl('extra')) . '&id=' . $choice->id }}">
                                        {{ trans('form.action_edit') }}
                                    </a>
                                    <a class="delete"
                                       href="{{ addRdrUrl(addExtraUrl('admin/poll-choices/id', adminUrl('extra')) . '&id=' . $choice->id) }}">
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