@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_topics_title'))
@section('page_description', trans('pages.admin_topics_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('topics') }}">{{ trans('pages.admin_topics_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_edit') }}</a></li>
</ol>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('topics/{id}', ['id'=> $topic->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('topics/{id}', ['id'=> $topic->id])) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('topics/create') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.topic_lc', 1) }}
                    </a>
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('label.topic_lc', 1) }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
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
                            <?php
                            $trans = $topic->translate($locale);
                            $name = $trans ? $trans->name : '';
                            $slug = $trans ? $trans->slug : '';
                            $description = $trans ? $trans->description : '';
                            ?>
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="{{ localeInputId('tab', $locale) }}">
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                                           name="{{ localeInputName('name', $locale) }}"
                                           placeholder="{{ trans('label.name') }}" type="text" value="{{ $name }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                    <textarea rows="5" class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                              name="{{ localeInputName('description', $locale) }}"
                                              placeholder="{{ trans('label.description') }}">{{ $description }}</textarea>
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div>
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('topics') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection