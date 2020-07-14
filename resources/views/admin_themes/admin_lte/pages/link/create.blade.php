@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_links_title'))
@section('page_description', trans('pages.admin_links_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('links') }}">{{ trans('pages.admin_links_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_add') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('links') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12">
                <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.link_lc', 1) }}</h4>
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
                            <label class="required" for="inputLinkCategories">{{ trans_choice('label.category', 2) }}</label>
                            <select id="inputLinkCategories" class="form-control select2" name="categories[]" multiple="multiple" required
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
                    <label for="inputImage">{{ trans('label.picture') }}</label>
                    <div class="input-group">
                        <input class="form-control" id="inputImage" name="image"
                               placeholder="{{ trans('label.picture') }}" type="text" value="{{ old('image') }}">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary image-from-documents"
                                    data-input-id="inputImage">
                                <i class="fa fa-server"></i>
                            </button>
                        </div>
                    </div>
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
                                    <label class="required separated" for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                                           name="{{ localeInputName('name', $locale) }}" type="text"
                                           placeholder="{{ trans('label.name') }}" value="{{ oldLocaleInput('name', $locale) }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                           name="{{ localeInputName('description', $locale) }}" type="text"
                                           placeholder="{{ trans('label.description') }}" value="{{ oldLocaleInput('description', $locale) }}">
                                </div>
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputUrl', $locale) }}">{{ trans('label.url') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputUrl', $locale) }}"
                                           name="{{ localeInputName('url', $locale) }}" type="text"
                                           placeholder="{{ trans('label.url') }}" value="{{ oldLocaleInput('url', $locale) }}">
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('links') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection