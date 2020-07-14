@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_links_title'))
@section('page_description', trans('pages.admin_links_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('links') }}">{{ trans('pages.admin_links_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_edit') }}</a></li>
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
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('links/{id}', ['id'=> $link->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('links/{id}', ['id'=> $link->id])) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('links/create') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.link', 1) }}
                    </a>
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('label.link_lc', 1) }}</h4>
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
                              <label class="required" for="inputCategories">{{ trans_choice('label.category', 2) }}</label>
                              <select id="inputCategories"  class="form-control select2" name="categories[]" multiple="multiple" required
                                      data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.link_category', 2) }}" style="width: 100%;">
                                  @foreach ($categories as $category)
                                      <option value="{{ $category->id }}"{{ $link_categories->contains('id', $category->id) ? ' selected' : '' }}>
                                          {{ $category->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                </div>
                <div class="form-group">
                    <label for="inputImage">
                        {{ trans('label.picture') }}
                        @if(!empty($link->image))
                            (<a class="open-window" href="{{ $link->image }}"
                               data-name="_blank" data-width="800" data-height="600">
                                <i class="fa fa-external-link"></i>
                            </a>)
                        @endif
                    </label>
                    <div class="input-group">
                        <input class="form-control" id="inputImage" name="image" placeholder="{{ trans('label.picture') }}" type="text" value="{{ $link->image }}">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary image-from-documents"
                                    data-input-id="inputImage">
                                <i class="fa fa-server"></i>
                            </button>
                        </div>
                    </div>
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
                            $trans = $link->translate($locale);
                            $name = $trans ? $trans->name : '';
                            $description = $trans ? $trans->description : '';
                            $url = $trans ? $trans->url : '';
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
                                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                           name="{{ localeInputName('description', $locale) }}"
                                           placeholder="{{ trans('label.description') }}" type="text" value="{{ $description }}">
                                </div>
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputUrl', $locale) }}">
                                        {{ trans('label.url') }}
                                        @if(!empty($url))
                                            (<a class="open-window" href="{{ $url }}"
                                                data-name="_blank" data-width="800" data-height="600">
                                                <i class="fa fa-external-link"></i>
                                            </a>)
                                        @endif
                                    </label>
                                    <input class="form-control" id="{{ localeInputId('inputUrl', $locale) }}"
                                           name="{{ localeInputName('url', $locale) }}"
                                           placeholder="{{ trans('label.url') }}" type="text" value="{{ $url }}">
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div>
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('links') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection