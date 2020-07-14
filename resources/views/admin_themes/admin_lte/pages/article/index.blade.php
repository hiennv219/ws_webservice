@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_articles_title'))
@section('page_description', trans('pages.admin_articles_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('articles') }}">{{ trans('pages.admin_articles_title') }}</a></li>
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
            function templateRender(item) {
                if (item.loading) return item.text;

                return '<div class="media">' +
                    '<div class="media-left"><img class="width-120" src="' + item.url_avatar_thumb + '"></div>' +
                    '<div class="media-body">' +
                    '<h4><strong>#' + item.id + ' - ' + item.display_name + '</strong> (' + item.name + ')</h4>' +
                    '<p>{{ trans('label.email') }}: ' + item.email + '.' +
                    '<br>Skype ID: ' + item.skype_id + '.' +
                    '<br>{{ trans('label.phone') }}: ' + item.phone + '.</p>'+
                    '</div>' +
                    '</div>';
            }

            function dataSelection(item) {
                return item.id != '' ? item.display_name + ' (' + item.email + ')' : item.text;
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

            var $inputAuthor = $('#inputAuthor');
            initAjaxSelect2($inputAuthor, KATNISS_WEB_API_URL + '/authors', templateRender, dataSelection, function (response) {
                return response._success ? response._data.authors : [];
            }, dataMore);
            $inputAuthor.on('change', function () {
                $('#inputAuthorHidden').val($(this).val());
            });

            $('#inputCategories').select2();

            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="search-modal" tabindex="false" role="dialog" aria-labelledby="search-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="search-modal-title">{{ trans('form.action_search') }}</h4>
                </div>
                <form>
                    <div id="search-modal-content" class="modal-body">
                        <div class="form-group">
                            <label for="inputTitle" class="control-label">{{ trans('label.title') }}</label>
                            <input id="inputTitle" type="text" class="form-control" value="{{ $search_title }}"
                                   name="title" placeholder="{{ trans('label.title') }}">
                        </div>
                        <div class="form-group">
                            <label for="inputAuthor" class="control-label">{{ trans('label.author') }}</label>
                            @if(!empty($search_author))
                                <span class="small">({{ trans('label._current', ['current' => 'ID']) }}: {{ $search_author }})</span>
                            @endif
                            <select id="inputAuthor" class="form-control select2" style="width: 100%;"
                                    data-placeholder="- {{ trans('form.action_select') }} {{ trans('label.author') }} -">
                            </select>
                            <input id="inputAuthorHidden" type="hidden" name="author" value="{{ $search_author }}">
                        </div>
                        <div class="form-group">
                            <label for="inputCategories">{{ trans_choice('label.category', 2) }}</label>
                            <select id="inputCategories" class="form-control select2" name="categories[]" multiple="multiple"
                                    data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.category', 2) }}" style="width: 100%;">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"{{ in_array($category->id, $search_categories) ? ' selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                        <a role="button" class="btn btn-warning {{ $on_searching ? '' : 'hide' }}" href="{{ $clear_search_url }}">
                            {{ trans('form.action_clear_search') }}
                        </a>
                        <button type="submit" class="btn btn-primary">{{ trans('form.action_search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('articles/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.article_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.article_lc', 2)]) }}</h3>
                    <div class="box-tools">
                        <button type="button" class="btn {{ $on_searching ? 'btn-warning' : 'btn-primary' }} btn-sm" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i> {{ trans('form.action_search') }}
                        </button>
                    </div>
                </div><!-- /.box-header -->
                @if($articles->count()>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.title') }}</th>
                                    <th>{{ trans('label.author') }}</th>
                                    <th>{{ trans('label.slug') }}</th>
                                    <th>{{ trans_choice('label.category', 2) }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th class="order-col-1"></th>
                                    <th>{{ trans('label.title') }}</th>
                                    <th>{{ trans('label.author') }}</th>
                                    <th>{{ trans('label.slug') }}</th>
                                    <th>{{ trans_choice('label.category', 2) }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td class="text-center">
                                            <a target="_blank" href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        </td>
                                        <td>{{ $article->title }}</td>
                                        <td>{{ $article->author->display_name }}</td>
                                        <td>{{ $article->slug }}</td>
                                        <td>{{ $article->categories->implode('name', ', ') }}</td>
                                        <td>
                                              <a href="{{ adminUrl('articles/{id}/edit', ['id'=> $article->id]) }}">
                                                  {{ trans('form.action_edit') }}
                                              </a>
                                              <a class="delete" href="{{ addRdrUrl(adminUrl('articles/{id}', ['id'=> $article->id])) }}">
                                                  {{ trans('form.action_delete') }}
                                              </a>
                                        </td>
                                    </tr>
                                @endforeach
                             </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{ $pagination }}
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection