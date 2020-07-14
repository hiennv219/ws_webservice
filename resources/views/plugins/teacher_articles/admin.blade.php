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
                return item.id != '' && item.display_name ? item.display_name + ' (' + item.email + ')' : item.text;
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

            function initAjaxTeachers($selector) {
                initAjaxSelect2($selector, KATNISS_WEB_API_URL + '/teachers', templateRender, dataSelection, function (response) {
                    return response._success ? response._data.teachers : [];
                }, dataMore);
            }

            initAjaxTeachers($('.select2'));

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
                    <h3 class="box-title">{{ trans('label.status_selected') }} {{ trans_choice('label.teacher_lc', 2) }}</h3>
                </div>
                <div class="box-body">
                    @foreach($teachers as $teacher)
                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="teachers[]" value="{{ $teacher->id }}" checked>
                                &nbsp;
                                <a href="{{ adminUrl('teachers/{id}', ['id' => $teacher->user_id]) }}">
                                    <strong>{{ $teacher->userProfile->display_name }}</strong> ({{ $teacher->userProfile->email }})
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="inputTeachers">{{ trans('form.action_add') }} {{ trans_choice('label.teacher_lc', 2) }}</label>
                <select id="inputTeachers" class="form-control select2" name="teachers[]" multiple style="width: 100%;">
                </select>
            </div>
            <div class="form-group">
                <label for="inputNumberOfItems">{{ trans('teacher_articles.number_of_items') }}</label>
                <input id="inputNumberOfItems" type="number" class="form-control"
                       name="number_of_items" value="{{ $number_of_items }}">
            </div>
        </div>
    </div>
@endsection
