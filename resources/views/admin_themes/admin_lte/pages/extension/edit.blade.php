@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_extensions_title'))
@section('page_description', trans('pages.admin_extensions_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('extensions') }}">{{ trans('pages.admin_extensions_title') }}</a>
        <li><a href="#">{{ trans('form.action_edit') }}</a></li>
    </ol>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('extensions/{name}', ['name' => $extension->getName()]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ $extension->getDisplayName() }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @include($extension_view)

                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('extensions') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection