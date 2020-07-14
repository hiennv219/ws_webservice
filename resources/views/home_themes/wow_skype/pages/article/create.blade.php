@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('medium-editor-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('medium-editor-theme-default') }}">
    <link rel="stylesheet" href="{{ libraryAsset('medium-editor-insert-plugin/css/medium-editor-insert-plugin.css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('jQuery-File-Upload/css/jquery.fileupload.css') }}">
@endsection
@section('extended_styles')
    <style>#inputTitle{overflow: hidden}</style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ _kExternalLink('medium-editor-js') }}"></script>
    <script src="{{ _kExternalLink('handlebars-runtime') }}"></script>
    <script src="{{ libraryAsset('jquery-sortable-min.js') }}"></script>
    <script src="{{ libraryAsset('jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ libraryAsset('jQuery-File-Upload/js/jquery.iframe-transport.js') }}"></script>
    <script src="{{ libraryAsset('jQuery-File-Upload/js/jquery.fileupload.js') }}"></script>
    <script src="{{ libraryAsset('medium-editor-insert-plugin/js/medium-editor-insert-plugin.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            });
            var editor = new MediumEditor('.medium-editor', {
                toolbar: {
                    buttons: [
                        'bold', 'italic', 'underline', 'anchor' ,
                        'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull',
                        'h2', 'h3', 'h4', 'quote', 'orderedlist', 'unorderedlist',
                        'indent', 'outdent'
                    ]
                },
                buttonLabels: 'fontawesome',
                placeholder: {
                    text: '{{ trans('label.content_help_uc') }}'
                }
            });
            $('.medium-editor').mediumInsert({
                editor: editor,
                addons: {
                    images: {
                        fileUploadOptions: {
                            url: '{{ webApiUrl('upload/blue-imp') }}',
                            paramName: 'image_file',
                            formData: {
                                _token: '{{ csrf_token() }}'
                            }
                        },
                        deleteScript: '{{ webApiUrl('upload/blue-imp') }}',
                        deleteMethod: 'DELETE',
                        fileDeleteOptions: {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        },
                        captionPlaceholder: '{{ trans('medium_editor.caption_image') }}',
                        messages: {
                            acceptFileTypesError: '{{ trans('medium_editor.support_image') }} ',
                            maxFileSizeError: '{{ trans('medium_editor.size_image') }} '
                        }
                    },
                    embeds: { // (object) Embeds addon configuration
                        placeholder: '{{ trans('medium_editor.help_embed') }}', // (string) Placeholder displayed when entering URL to embed
                        captionPlaceholder: '{{ trans('medium_editor.caption_embed') }}',
                        oembedProxy: null
                    }
                }
            });

            $('#inputTitle').on('keyup', function (e) {
                var $this = $(this);
                $this.height(0);
                $this.height($this.get(0).scrollHeight);
            });

            var _$featuredImage = $('#featured-image');
            var _$changeFeaturedImage = $('#change-featured-image');
            var _$changeFeaturedImagePrev = _$changeFeaturedImage.prev('span');
            var _$removeFeaturedImage = $('#remove-featured-image');
            var _$inputFeaturedImage = $('[name="featured_image"]');
            var changeLabel = '{{ trans('form.action_change') }} {{ trans('label.cover_image') }}';
            var addLabel = '{{ trans('form.action_add') }} {{ trans('label.cover_image') }}';
            _$changeFeaturedImage.fileupload({
                url: '{{ webApiUrl('upload/blue-imp') }}',
                paramName: 'image_file',
                formData: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                done: function (e, data) {
                    if (data.result.files[0]) {
                        _$removeFeaturedImage.removeClass('hide');
                        _$changeFeaturedImagePrev.text(changeLabel);
                        var url = data.result.files[0].url;
                        _$featuredImage.html('<img src="' + url + '">');
                        _$inputFeaturedImage.val(url);
                    }
                }
            });
            _$removeFeaturedImage.on('click', function (e) {
                e.preventDefault();
                _$inputFeaturedImage.val('');
                _$featuredImage.html('');
                _$changeFeaturedImagePrev.text(addLabel);
                _$removeFeaturedImage.addClass('hide');
            });

            $('#create-article-form').on('submit', function (e) {
                e.preventDefault();
                var $inputContent = $('#inputContent')
                var $html = $($inputContent.val()).not(':last'); // remove image insert class
                var $wrapHtml = $('<div></div>').append($html);
                $inputContent.val($wrapHtml.html());
                this.submit();
            });
        });
    </script>
@endsection
@section('main_content')
    <div id="page-article">
        <div class="row">
            <div class="col-md-8">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ homeUrl('knowledge') }}">{{ trans('pages.home_knowledge_title') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ homeUrl('knowledge/articles') }}">{{ trans('pages.home_articles_title') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ trans('form.action_create') }} {{ trans_choice('label.article_lc', 1) }}
                    </li>
                </ol>
                <?php $old_featured_image = old('featured_image', '') ?>
                <form id="create-article-form" method="post" action="{{ homeUrl('knowledge/articles') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="featured_image" value="{{ $old_featured_image }}">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="inputTitle" class="sr-only">{{ trans('label.title') }}</label>
                        <textarea id="inputTitle" name="title" placeholder="{{ trans('label.title') }}"
                                  class="form-control as-text h1 bold-700 color-master"
                                  rows="1" cols="5" required>{{ old('title') }}</textarea>
                    </div>
                    <div class="master-slave-bar clearfix">
                        <div class="bar pull-left"></div>
                        <div class="bar pull-right"></div>
                    </div>
                    <div class="article-meta margin-v-10">
                        <a href="{{ homeUrl('knowledge/authors/{id}', ['id' => $auth_user->id]) }}">
                            <img class="img-circle border-solid border-master width-30"
                                 src="{{ $auth_user->url_avatar_thumb }}">
                            {{ $auth_user->display_name }}
                        </a>
                        <span class="color-lighter hidden-sm hidden-xs">
                            / {{ trans('datetime.today') }} &#64; {{ date('H:i') }}
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="inputArticleCategories" class="sr-only">{{ trans_choice('label.category', 2) }}</label>
                        <select id="inputArticleCategories" class="form-control select2" name="categories[]" multiple="multiple"
                                data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.category', 2) }}" style="width: 100%;">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"{{ in_array($category->id, old('categories', [])) ? ' selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="featured-image" class="image-cover margin-bottom-10">
                        @if(!empty($old_featured_image))
                            <img src="{{ $old_featured_image }}">
                        @endif
                    </div>
                    <div class="form-group">
                        <span class="btn btn-primary fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>{{ empty($old_featured_image) ? trans('form.action_add') : trans('form.action_change') }} {{ trans('label.cover_image') }}</span>
                            <input id="change-featured-image" type="file" name="image_file">
                        </span>
                        <button id="remove-featured-image" type="button" class="btn btn-warning{{ empty($old_featured_image) ? ' hide' : '' }}">
                            <i class="glyphicon glyphicon-remove"></i>
                            {{ trans('form.action_delete') }} {{ trans('label.cover_image') }}
                        </button>
                    </div>
                    <div class="form-group article-responsive">
                        <label for="inputContent" class="sr-only">{{ trans('label.content') }}</label>
                        <textarea id="inputContent" name="content" placeholder="{{ trans('label.content') }}"
                                  class="medium-editor no-outline" rows="10" cols="5" required>{{ old('content') }}</textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">{{ trans('form.action_create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection