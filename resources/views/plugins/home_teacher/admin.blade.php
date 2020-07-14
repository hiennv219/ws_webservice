@extends('plugins.default_widget.admin')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    @parent
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

            function initAjaxTeacher($selector) {
                initAjaxSelect2($selector, KATNISS_WEB_API_URL + '/teachers', templateRender, dataSelection, function (response) {
                    return response._success ? response._data.teachers : [];
                }, dataMore);
            }

            initAjaxTeacher($('.select2'));

            $('button.add-more-teacher').on('click', function (e) {
                e.preventDefault();
                initAjaxTeacher($('.teacher-list').append($('#teacher-item-template').html()).children(':last').find('.select2'));
                $(this).hide();
            });
            $(document).on('click', 'button.delete-teacher', function (e) {
                e.preventDefault();
                $(this).closest('.teacher-item').remove();
            });
        });
    </script>
    <script id="teacher-item-template" type="text/html">
        <div class="teacher-item">
            <hr style="border-color:#d2d6de">
            <button type="button" class="btn btn-danger delete-teacher margin-bottom">{{ trans('form.action_delete') }}</button>
            <div class="form-group">
                <label for="inputTeacher_{{ $last_order }}" class="control-label">{{ trans_choice('label.teacher', 1) }}</label>
                <select id="inputTeacher_{{ $last_order }}" class="form-control select2" name="teachers[]" style="width: 100%;"
                        data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -">
                    <option value="">
                        - {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -
                    </option>
                </select>
            </div>
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                            <a href="#tab{{ $last_order }}_{{ $locale }}" data-toggle="tab">
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                        <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab{{ $last_order }}_{{ $locale }}">
                            <div class="form-group">
                                <label for="{{ localeInputId('inputTagLine_' . $last_order, $locale) }}">{{ trans('label.tag_line') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputTagLine_' . $last_order, $locale) }}"
                                       name="{{ localeInputName('tag_lines', $locale, true) }}" type="text"
                                       placeholder="{{ trans('label.tag_line') }}">
                            </div>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputReview_' . $last_order, $locale) }}">{{ trans('label.review') }}</label>
                                <textarea class="form-control" id="{{ localeInputId('inputReview_' . $last_order, $locale) }}"
                                          name="{{ localeInputName('reviews', $locale, true) }}" rows="3" cols="3"
                                          placeholder="{{ trans('label.review') }}"></textarea>
                            </div>
                        </div><!-- /.tab-pane -->
                    @endforeach
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
        </div>
    </script>
@endsection
@section('extended_widget_bottom')
    <div class="teacher-list">
        @for($i = 0; $i < $last_order; ++$i)
            <div class="teacher-item">
                <hr style="border-color:#d2d6de">
                <button type="button" class="btn btn-danger delete-teacher margin-bottom">{{ trans('form.action_delete') }}</button>
                <div class="form-group">
                    <label for="inputTeacher_{{ $i }}" class="control-label">{{ trans_choice('label.teacher', 1) }}</label>
                    <select id="inputTeacher_{{ $i }}" class="form-control select2" name="teachers[]" style="width: 100%;"
                            data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -">
                        <option value="">
                            - {{ trans('form.action_select') }} {{ trans_choice('label.teacher', 1) }} -
                        </option>
                        @if(isset($teachers[$i]))
                            <option value="{{ $teachers[$i]['id'] }}" selected>{{ $teachers[$i]['display_name'] }} ({{ $teachers[$i]['email'] }})</option>
                        @endif
                    </select>
                </div>
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                            <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                                <a href="#tab{{ $i }}_{{ $locale }}" data-toggle="tab">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab{{ $i }}_{{ $locale }}">
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputTagLine_' . $i, $locale) }}">{{ trans('label.tag_line') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputTagLine_' . $i, $locale) }}"
                                           name="{{ localeInputName('tag_lines', $locale, true) }}" type="text"
                                           placeholder="{{ trans('label.tag_line') }}" value="{{ $widget->getProperty('tag_lines', $locale, true, $i) }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputReview_' . $i, $locale) }}">{{ trans('label.review') }}</label>
                                    <textarea class="form-control" id="{{ localeInputId('inputReview_' . $i, $locale) }}"
                                              name="{{ localeInputName('reviews', $locale, true) }}" rows="3" cols="3"
                                              placeholder="{{ trans('label.review') }}">{{ $widget->getProperty('reviews', $locale, true, $i) }}</textarea>
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
            </div>
        @endfor
    </div>
    <button type="button" class="btn btn-success add-more-teacher">
        {{ trans('form.action_add_more') }} {{ trans_choice('label.teacher_lc', 1) }}
    </button>
    <hr style="border-color:#d2d6de">
@endsection