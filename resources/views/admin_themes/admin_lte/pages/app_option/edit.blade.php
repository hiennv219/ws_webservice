@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_app_options_title'))
@section('page_description', trans('pages.admin_app_options_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('app-options') }}">{{ trans('pages.admin_app_options_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_edit') }}</a></li>
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
                <a class="btn btn-warning delete"
                   href="{{ addErrorUrl(adminUrl('app-options/{id}', ['id'=> $app_option->id])) }}">
                    {{ trans('form.action_delete') }}
                </a>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.app_option_lc', 1) }} - <em>{{ $app_option->key }}</em>
                    </h3>
                </div>
                <form method="post" action="{{ adminUrl('app-options/{id}', ['id' => $app_option->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                        <div class="form-group">
                            <label for="inputKey">{{ trans('label.key') }}</label>
                            <input class="form-control" id="inputKey" name="key" placeholder="{{ trans('label.key') }}"
                                   type="text" maxlength="255" disabled value="{{ $app_option->key }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputValue">{{ trans('label.value') }}</label>
                            <textarea class="form-control" id="inputValue" name="raw_value" cols="10" rows="10"
                                      placeholder="{{ trans('label.value') }}">{{ $app_option->rawValue }}</textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-default pull-right" href="{{ adminUrl('app-options') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection