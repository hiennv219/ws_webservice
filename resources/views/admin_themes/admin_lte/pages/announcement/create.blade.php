@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_announcements_title'))
@section('page_description', trans('pages.admin_announcements_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('announcements') }}">{{ trans('pages.admin_announcements_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_add') }}</a></li>
</ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('extended_styles')
    <style>
        .select2-dropdown {
            min-width: 320px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('#inputToRoles').select2();
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

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

            initAjaxSelect2($('#inputToUsers'), KATNISS_WEB_API_URL + '/users?normal_role=1', templateRender, dataSelection, function (response) {
                return response._success ? response._data.users : [];
            }, dataMore);
        });
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('announcements') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.announcement_lc', 1) }}</h4>
                    </div>
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="inputTitle" class="control-label">{{ trans('label.title') }}</label>
                            <input type="text" placeholder="{{ trans('label.title') }}" value="{{ old('title') }}"
                                   class="form-control" id="inputTitle" name="title">
                        </div>
                        <div class="form-group">
                            <label for="inputContent" class="control-label">{{ trans('label.content') }}</label>
                            <textarea placeholder="{{ trans('label.content') }}" class="form-control" rows="10" cols="5"
                                      id="inputTitle" name="content" required>{{ old('content') }}</textarea>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="checkbox icheck">
                                <label for="inputToAll">
                                    <input id="inputToAll" name="to[all]" type="checkbox" value="1" checked>
                                    &nbsp; {{ trans('label.to_all') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputToRoles">{{ trans('label.to_roles') }}</label>
                            <select id="inputToRoles" class="form-control select2" name="to[roles][]" multiple style="width: 100%"
                                    data-placeholder="{{ trans('label.to_roles') }}">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputToUsers" class="control-label">{{ trans('label.to_users') }}</label>
                            <select id="inputToUsers" class="form-control select2" name="to[users][]" multiple style="width: 100%;"
                                    data-placeholder="{{ trans('label.to_users') }}">
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                        <div class="pull-right">
                            <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                            <a role="button" class="btn btn-warning" href="{{ adminUrl('announcements') }}">{{ trans('form.action_cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection