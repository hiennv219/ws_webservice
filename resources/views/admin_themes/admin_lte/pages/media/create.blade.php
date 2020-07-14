@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_media_items_title'))
@section('page_description', trans('pages.admin_media_items_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('media-items') }}">{{ trans('pages.admin_media_items_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_add') }}</a></li>
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
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('media-items') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12">
                <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.media_lc', 1) }}</h4>
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
                            <label for="inputLinkCategories">{{ trans_choice('label.category', 2) }}</label>
                            <select id="inputLinkCategories" class="form-control select2" name="categories[]" multiple="multiple"
                                    data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.category', 2) }}" style="width: 100%;">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"{{ in_array($category->id, old('categories', [])) ? ' selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="required" for="inputUrl">{{ trans('label.url') }}</label>
                    <div class="input-group">
                        <input class="form-control" id="inputUrl" name="url" placeholder="{{ trans('label.url') }}"
                               type="text" required value="{{ old('url') }}">
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
                                    <input type="radio" name="type" value="{{ $value }}"{{ old('type') == $value ? ' checked' : '' }}>
                                    {{ $text }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
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
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="{{ localeInputId('tab', $locale) }}">
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputTitle', $locale) }}">{{ trans('label.title') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputTitle', $locale) }}"
                                           name="{{ localeInputName('title', $locale) }}" type="text"
                                           placeholder="{{ trans('label.title') }}" value="{{ oldLocaleInput('title', $locale) }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                           name="{{ localeInputName('description', $locale) }}" type="text"
                                           placeholder="{{ trans('label.description') }}" value="{{ oldLocaleInput('description', $locale) }}">
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('media-items') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection