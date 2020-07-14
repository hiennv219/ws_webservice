@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_study_courses_title'))
@section('page_description', trans('pages.admin_study_courses_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('study-courses') }}">{{ trans('pages.admin_study_courses_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('study-courses/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.study_course_lc', 1) }}
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
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.study_course_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
                @if($study_courses->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans('label.description') }}</th>
                                    <th>{{ trans('label.sort_order') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans('label.description') }}</th>
                                    <th>{{ trans('label.sort_order') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($study_courses as $study_course)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td>{{ $study_course->name }}</td>
                                        <td>{{ $study_course->description }}</td>
                                        <td>{{ $study_course->order }}</td>
                                        <td>
                                            <a href="{{ adminUrl('study-courses/{id}/edit', ['id'=> $study_course->id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('study-courses/{id}', ['id'=> $study_course->id])) }}">
                                                {{ trans('form.action_delete') }}
                                            </a>
                                        </td>
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