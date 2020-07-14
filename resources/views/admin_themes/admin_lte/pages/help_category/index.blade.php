@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_help_categories_title'))
@section('page_description', trans('pages.admin_help_categories_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('help-categories') }}">{{ trans('pages.admin_help_categories_title') }}</a></li>
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
                <a class="btn btn-primary" href="{{ adminUrl('help-categories/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.category_lc', 1) }}
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
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.category_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
                @if($categories->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans('label.slug') }}</th>
                                    <th>{{ trans('label.sort_order') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.name') }}</th>
                                    <th>{{ trans('label.slug') }}</th>
                                    <th>{{ trans('label.sort_order') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>{{ $category->order }}</td>
                                        <td>
                                            <a href="{{ adminUrl('help-categories/{id}/edit', ['id'=> $category->id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a href="{{ adminUrl('help-categories/{id}/sort', ['id'=> $category->id]) }}">
                                                {{ trans('form.action_sort') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('help-categories/{id}', ['id'=> $category->id])) }}">
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