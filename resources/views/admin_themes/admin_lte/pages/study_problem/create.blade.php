@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_study_problems_title'))
@section('page_description', trans('pages.admin_study_problems_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('study-problems') }}">{{ trans('pages.admin_study_problems_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_add') }}</a></li>
</ol>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('study-problems') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12">
                <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.study_problem_lc', 1) }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                    </div>
                @endif
                <div class="form-group">
                    <label for="inputOrder">{{ trans('label.sort_order') }}</label>
                    <input class="form-control" id="inputOrder" name="order"
                           placeholder="{{ trans('label.sort_order') }}" type="text" value="{{ old('order', 0) }}">
                </div>
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                            <a href="#{{ localeInputId('tab', $locale) }}" data-toggle="tab">
                            {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                    </ul>
                    <div class="tab-content">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <div class="tab-pane{!! $locale == $site_locale ? ' active' : '' !!}" id="{{ localeInputId('tab', $locale) }}">
                            <div class="form-group">
                                <label class="required separated" for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                                       name="{{ localeInputName('name', $locale) }}" type="text"
                                       placeholder="{{ trans('label.name') }}" value="{{ oldLocaleInput('name', $locale) }}">
                            </div>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                <textarea rows="5" class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                          name="{{ localeInputName('description', $locale) }}"
                                          placeholder="{{ trans('label.description') }}">{{ oldLocaleInput('description', $locale) }}</textarea>
                            </div>
                        </div><!-- /.tab-pane -->
                    @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('study-problems') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection