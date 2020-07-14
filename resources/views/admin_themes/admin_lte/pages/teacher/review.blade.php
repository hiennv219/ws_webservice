@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_teacher_review_title'))
@section('page_description', trans('pages.admin_teacher_review_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_teacher_review_title') }}</a></li>
        <li><a href="{{ adminUrl('approved-teachers') }}">{{ trans('pages.admin_teacher_review_desc') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_put($('a.reject'), '{{ trans('form.action_reject') }}', '{{ trans('label.wanna_reject', ['name' => '']) }}');
            x_modal_put(
                $('a.full-schedule'),
                '{{ trans('form.action_change_to') }} {{ trans('label.status_full_schedule') }}',
                '{{ trans('label.wanna_change_to', ['name' => trans('label.status_full_schedule')]) }}');
            x_modal_put(
                $('a.available'),
                '{{ trans('form.action_change_to') }} {{ trans('label.status_teaching_available') }}',
                '{{ trans('label.wanna_change_to', ['name' => trans('label.status_teaching_available')]) }}');
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.review', 2)]) }}</h3>

                </div><!-- /.box-header -->
                @if($reviews->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-5">{{ trans('label.reviewer') }}</th>
                                    <th class="order-col-5">{{ trans('label.rate') }}</th>
                                    <th class="order-col-5">{{ trans('label.review_time') }}</th>
                                    <th>{{ trans('label.review_content') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-5">{{ trans('label.reviewer') }}</th>
                                    <th class="order-col-5">{{ trans('label.rate') }}</th>
                                    <th class="order-col-5">{{ trans('label.review_time') }}</th>

                                    <th>{{ trans('label.review_content') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($reviews as $review)
                                    <tr>
                                        <td>{{ ++$start_order }}</td>
                                        <td>{{ $review->user->display_name}}</td>
                                        <td>{{ $review->rate }}</td>
                                        <td>{{ $review->created_at }}</td>
                                        <td>{{ $review->review }}</td>

                                    </tr>
                                @endforeach
                             </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{ $pagination }}
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection