@extends('plugins.default_widget.admin')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            function templateRender(item) {
                if (item.loading) return item.text;

                return '<div class="media">' +
                    '<div class="media-left">' +
                    (item.featured_image ?
                        '<img class="width-120" src="' + item.featured_image + '">' : '<div class="width-120">&nbsp;</div>') +
                    '</div>' +
                    '<div class="media-body">' +
                    '<h4><strong>#' + item.id + ' - ' + item.title + '</strong> (' + item.author.display_name + ')</h4>' +
                    '<p>' + item.short_content + '.</p>'+
                    '</div>' +
                    '</div>';
            }

            function dataSelection(item) {
                return item.id != '' && item.title ? item.title + ' (' + item.author.display_name + ')' : item.text;
            }

            function dataMore(response) {
                return response._success
                    && response._data.pagination.last != 0
                    && response._data.pagination.last != response._data.pagination.current;
            }

            function initAjaxSelect2($selector, url, templateFunc, selectionFunc, resultFunc, moreFunc) {
                $selector.select2({
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            return {
                                results: resultFunc(data),
                                pagination: {
                                    more: moreFunc(data)
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: templateFunc, // omitted for brevity, see the source of this page
                    templateSelection: selectionFunc // omitted for brevity, see the source of this page
                });
            }

            function initAjaxArticle($selector) {
                initAjaxSelect2($selector, KATNISS_WEB_API_URL + '/articles', templateRender, dataSelection, function (response) {
                    return response._success ? response._data.articles : [];
                }, dataMore);
            }

            initAjaxArticle($('.select2'));

            $('.sortable').sortable({
                placeholder: 'sort-highlight',
                handle: '.handle',
                forcePlaceholderSize: true,
                zIndex: 999999
            });

            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
@section('extended_widget_top')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.action_sort') }} {{ trans_choice('label.article_lc', 2) }}</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list sortable">
                        @foreach($articles as $article)
                            <li>
                                <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <input type="checkbox" name="articles[]" value="{{ $article->id }}" checked>
                                <span class="text">
                                    <a href="{{ adminUrl('articles/{id}', ['id' => $article->id]) }}">
                                        {{ $article->title }}
                                    </a>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="inputArticles">{{ trans('form.action_add') }} {{ trans_choice('label.article_lc', 2) }}</label>
                <select id="inputArticles" class="form-control select2" name="articles[]" multiple style="width: 100%;">
                </select>
            </div>
            <div class="form-group">
                <div class="checkbox icheck">
                    <label for="inputShowButton">
                        <input id="inputShowButton" type="checkbox" name="show_button"
                               value="1"{{ $show_button == 1 ? ' checked' : '' }}>
                        &nbsp; {{ trans('featured_articles.show_button') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
@endsection
