@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_widgets_title'))
@section('page_description', trans('pages.admin_widgets_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('widgets') }}">{{ trans('pages.admin_widgets_title') }}</a>
        <li><a href="#">{{ trans('form.action_edit') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    @parent
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
            x_modal_put($('a.activate'), '{{ trans('form.action_activate') }}', '{{ trans('label.wanna_activate', ['name' => '']) }}');
            x_modal_put($('a.deactivate'), '{{ trans('form.action_deactivate') }}', '{{ trans('label.wanna_deactivate', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('widgets/{id}', ['id' => $themeWidget->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('widgets/{id}', ['id' => $themeWidget->id])) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    @if($themeWidget->active)
                        <a class="btn btn-success deactivate" href="{{ addErrorUrl(adminUrl('widgets/{id}', ['id'=> $themeWidget->id]) . '?deactivate=1') }}">{{ trans('form.action_deactivate') }}</a>
                    @else
                        <a class="btn btn-success activate" href="{{ addErrorUrl(adminUrl('widgets/{id}', ['id'=> $themeWidget->id]) . '?activate=1') }}">{{ trans('form.action_activate') }}</a>
                    @endif
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ $widget->getDisplayName() }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @include($widget_view)

                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('widgets') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection