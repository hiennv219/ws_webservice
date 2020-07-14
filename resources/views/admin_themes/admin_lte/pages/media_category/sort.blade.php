@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_media_categories_title'))
@section('page_description', trans('pages.admin_media_categories_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('media-categories') }}">{{ trans('pages.admin_media_categories_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_sort') }}</a></li>
    </ol>
@endsection
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
                    api.put('admin/media-categories/{{ $category->id }}', {
                        media_ids: items,
                        sort: 1
                    });
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
                   href="{{ addErrorUrl(adminUrl('media-categories/{id}', ['id'=> $category->id])) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a role="button" class="btn btn-primary"
                   href="{{ adminUrl('media-categories/{id}/edit', ['id'=> $category->id]) }}">
                    {{ trans('form.action_edit') }}
                </a>
                <a role="button" class="btn btn-primary pull-right"
                   href="{{ adminUrl('media-categories/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.category_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.action_sort') }} {{ trans_choice('label.category_lc', 1) }} - <em>{{ $category->name }}</em></h3>
                </div>
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if($media->count()>0)
                        <ul class="todo-list sortable" data-category="{{ $category->id }}">
                            @foreach($media as $item)
                                <li data-item="{{ $item->id }}">
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">{{ $item->title }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ trans('label.list_empty') }}</p>
                    @endif
                </div>
            </div>
            <div>
                <div class="pull-right">
                    <a role="button" class="btn btn-warning" href="{{ adminUrl('media-categories') }}">{{ trans('form.action_cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection