@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_media_items_title'))
@section('page_description', trans('pages.admin_media_items_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('media-items') }}">{{ trans('pages.admin_media_items_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_edit') }}</a></li>
</ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('extended_styles')
    <style>
        .form-group .list-inline li {
            padding-left: 0;
            padding-right: 10px;
        }
        .form-group .list-inline li label {
            font-weight: 400;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('.select2').select2();
            $('[type=radio]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('media-items/{id}', ['id'=> $media->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('media-items/{id}', ['id'=> $media->id])) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('media-items/create') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.link', 1) }}
                    </a>
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('label.category_lc', 1) }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                          <div class="form-group">
                              <label for="inputCategories">{{ trans_choice('label.category', 2) }}</label>
                              <select id="inputCategories"  class="form-control select2" name="categories[]" multiple="multiple"
                                      data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.category', 2) }}" style="width: 100%;">
                                  @foreach ($categories as $category)
                                      <option value="{{ $category->id }}"{{ $media_categories->contains('id', $category->id) ? ' selected' : '' }}>
                                          {{ $category->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                </div>
                <div class="form-group">
                    <label class="required" for="inputUrl">
                        {{ trans('label.url') }}
                        @if(!empty($media->url))
                            (<a class="open-window" href="{{ $media->url }}"
                               data-name="_blank" data-width="800" data-height="600">
                                <i class="fa fa-external-link"></i>
                            </a>)
                        @endif
                    </label>
                    <div class="input-group">
                        <input class="form-control" id="inputUrl" name="url" placeholder="{{ trans('label.url') }}"
                               type="text" required value="{{ $media->url }}">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary file-from-documents"
                                    data-input-id="inputUrl" data-document-types="images,video">
                                <i class="fa fa-server"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="required" for="inputType">{{ trans('label.type') }}</label>
                    <ul class="list-inline">
                        @foreach($types as $value => $text)
                            <li>
                                <label>
                                    <input type="radio" name="type" value="{{ $value }}"{{ $media->type == $value ? ' checked' : '' }}>
                                    {{ $text }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
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
                            $trans = $media->translate($locale);
                            $title = $trans ? $trans->title : '';
                            $description = $trans ? $trans->description : '';
                            ?>
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="{{ localeInputId('tab', $locale) }}">
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputTitle', $locale) }}">{{ trans('label.title') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputTitle', $locale) }}"
                                           name="{{ localeInputName('title', $locale) }}"
                                           placeholder="{{ trans('label.title') }}" type="text" value="{{ $title }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                           name="{{ localeInputName('description', $locale) }}"
                                           placeholder="{{ trans('label.description') }}" type="text" value="{{ $description }}">
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div>
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('media-items') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection