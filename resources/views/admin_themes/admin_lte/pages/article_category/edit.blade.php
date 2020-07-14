@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_article_categories_title'))
@section('page_description', trans('pages.admin_article_categories_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('article-categories') }}">{{ trans('pages.admin_article_categories_title') }}</a></li>
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
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <form class="check-slug" method="post" action="{{ adminUrl('article-categories/{id}', ['id'=> $category->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete"
                       href="{{ addErrorUrl(adminUrl('article-categories/{id}', ['id'=> $category->id])) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('article-categories/create') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.category_lc', 1) }}
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
                            <label for="inputParentId">{{ trans('label.category_parent') }}</label>
                            <select id="inputParentId" class="form-control select2" name="parent" style="width: 100%;"
                                    data-placeholder="{{ trans('form.action_select') }} {{ trans('label.category_parent') }}">
                                <option value="0">[{{ trans('label.not_set') }}]</option>
                            @foreach($categories as $parent_category)
                                @if($parent_category->id!=$category->id)
                                    <option value="{{ $parent_category->id }}"{{ $parent_category->id == $category->parent_id ? ' selected' : '' }}>
                                        {{ $parent_category->name }}
                                    </option>
                                @endif
                            @endforeach
                            </select>
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
                            $trans = $category->translate($locale);
                            $name = $trans ? $trans->name : '';
                            $slug = $trans ? $trans->slug : '';
                            $description = $trans ? $trans->description : '';
                            ?>
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="{{ localeInputId('tab', $locale) }}">
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                    <input class="form-control slug-from" id="{{ localeInputId('inputName', $locale) }}"
                                           name="{{ localeInputName('name', $locale) }}"
                                           placeholder="{{ trans('label.name') }}" type="text" value="{{ $name }}">
                                </div>
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputSlug', $locale) }}">{{ trans('label.slug') }}</label>
                                    <input class="form-control slug" id="{{ localeInputId('inputSlug', $locale) }}"
                                           name="{{ localeInputName('slug', $locale) }}"
                                           placeholder="{{ trans('label.slug') }}" type="text" value="{{ $slug }}">
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
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('article-categories') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection