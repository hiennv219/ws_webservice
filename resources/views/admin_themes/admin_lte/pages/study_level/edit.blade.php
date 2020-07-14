@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_study_levels_title'))
@section('page_description', trans('pages.admin_study_levels_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('study-levels') }}">{{ trans('pages.admin_study_levels_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_edit') }}</a></li>
</ol>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('study-levels/{id}', ['id'=> $study_level->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('study_levels/{id}', ['id'=> $study_level->id])) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('study-levels/create') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.study_level_lc', 1) }}
                    </a>
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('label.study_level_lc', 1) }}</h4>
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
                           placeholder="{{ trans('label.sort_order') }}" type="text" value="{{ $study_level->order }}">
                </div>
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
                            $trans = $study_level->translate($locale);
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
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('study-levels') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection