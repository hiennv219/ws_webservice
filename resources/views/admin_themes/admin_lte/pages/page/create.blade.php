@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_pages_title'))
@section('page_description', trans('pages.admin_pages_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('pages') }}">{{ trans('pages.admin_pages_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_add') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('ckeditor-4.5.5/ckeditor.js') }}"></script>
    <script src="{{ libraryAsset('ckeditor-4.5.5/adapters/jquery.js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('.select2').select2();
            $('.slug-from').each(function () {
                var $this = $(this);
                $this.registerSlugTo($this.closest('.tab-pane').find('.slug'));
            });
            var $slug = $('.slug');
            $slug.registerSlug();
            $('form.check-slug').on('submit', function () {
                var slugs = [];
                var unique = true;
                $slug.each(function () {
                    var slug = $(this).val();
                    if (slugs.indexOf(slug) != -1) {
                        unique = false;
                    }
                    else if (slug.trim().length != 0) {
                        slugs.push(slug);
                    }
                });
                if (!unique) {
                    x_modal_alert('{{ trans('validation.unique', ['attribute' => 'slug']) }}');
                    return false;
                }
            });
            $('.ck-editor').ckeditor({
                language: '{{ $site_locale }}',
                filebrowserBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}',
                filebrowserFlashBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=flash',
                filebrowserFlashUploadUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=flash',
                filebrowserImageBrowseLinkUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=images',
                filebrowserImageBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=images',
                customConfig: '{{ libraryAsset('ckeditor-4.5.5/config_typical.js') }}'
            });
        });
    </script>
@endsection
@section('page_content')
    <form class="check-slug" method="post" action="{{ adminUrl('pages') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12">
                <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.page_lc', 1) }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if(!empty($templates))
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="inputTemplate">{{ trans('label.post_template') }}</label>
                                <select id="inputTemplate" class="form-control select2" name="template" style="width: 100%;"
                                        data-placeholder="{{ trans('form.action_select') }} {{ trans('label.post_template') }}">
                                    <option value="0">[{{ trans('label.not_set') }}]</option>
                                    @foreach($templates as $templateValue => $templateText)
                                        <option value="{{ $templateValue }}"{{ $templateValue == old('template') ? ' selected' : '' }}>
                                            {{ $templateText }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label for="inputFeaturedImage">{{ trans('label.picture') }}</label>
                    <div class="input-group">
                        <input class="form-control" id="inputFeaturedImage" name="featured_image"
                               placeholder="{{ trans('label.picture') }}" type="text" value="{{ old('featured_image') }}">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary image-from-documents"
                                    data-input-id="inputFeaturedImage">
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
                                    <label class="required separated" for="{{ localeInputId('inputTitle', $locale) }}">{{ trans('label.title') }}</label>
                                    <input class="form-control slug-from" id="{{ localeInputId('inputTitle', $locale) }}"
                                           name="{{ localeInputName('title', $locale) }}" type="text" maxlength="255"
                                           placeholder="{{ trans('label.title') }}" value="{{ oldLocaleInput('title', $locale) }}">
                                </div>
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputSlug', $locale) }}">{{ trans('label.slug') }}</label>
                                    <input class="form-control slug" id="{{ localeInputId('inputSlug', $locale) }}"
                                           name="{{ localeInputName('slug', $locale) }}" type="text" maxlength="255"
                                           placeholder="{{ trans('label.slug') }}" value="{{ oldLocaleInput('slug', $locale) }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                           name="{{ localeInputName('description', $locale) }}" type="text" maxlength="255"
                                           placeholder="{{ trans('label.description') }}" value="{{ oldLocaleInput('description', $locale) }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputContent', $locale) }}">{{ trans('label.content') }}</label>
                                    <textarea cols="10" rows="10" class="form-control ck-editor" id="{{ localeInputId('inputContent', $locale) }}"
                                              name="{{ localeInputName('content', $locale) }}"
                                              placeholder="{{ trans('label.content') }}">{{ oldLocaleInput('content', $locale) }}</textarea>
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('pages') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection