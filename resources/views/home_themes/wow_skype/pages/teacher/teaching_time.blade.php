@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-teaching-time',
    'panel_heading' => trans('label.teaching_time'),
])
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') }}">
@endsection
@section('extended_styles')
    <style>
        input[type=checkbox], input[type=radio] {margin-top:2px}
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('moment/moment.min.js') }}"></script>
    <script src="{{ libraryAsset('moment/locale/' . $site_locale . '.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/inputmask.binding.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            });
            $('.time-picker').datetimepicker({
                format: 'HH:mm'
            });
            $('[name="times[]"]').on('change', function () {
                var $this = $(this);
                if ($this.is(':checked')) {
                    $this.closest('.time-item').find('input[type="text"]').prop('disabled', false);
                }
                else {
                    $this.closest('.time-item').find('input[type="text"]').prop('disabled', true);
                }
            });
        });
    </script>
@endsection
@section('profile_content')
    <form method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @include('messages_after_action')
        <div class="form-group">
            <label for="inputTimeZone">{{ trans('label.timezone') }}:</label>
            <select id="inputTimeZone" class="form-control select2" name="timezone" style="width: 100%;">
                {!!  timeZoneListAsOptions(settings()->timezone) !!}
            </select>
        </div>
        <div class="form-group">
            <label>{{ trans('label.available_times') }}:</label>
            @for($i = 0; $i < 7; ++$i)
                <div class="time-item">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="times[]" value="{{ $i }}"{{ in_array($i, $available_times) ? ' checked' : '' }}>
                            <strong>{{ trans('datetime.day_' . $i) }}</strong>
                        </label>
                    </div>
                    <div class="form-inline margin-left-20">
                        <div class="form-group">
                            <label for="inputFrom_{{ $i }}" class="bold-400">{{ trans('label.from') }}</label>
                            <input id="inputFrom_{{ $i }}" type="text" class="form-control time-picker"
                                   name="range_from[{{ $i }}]"{{ in_array($i, $available_times) ? '' : ' disabled' }}
                                   value="{{ !empty($available_range_from[$i]) ? $available_range_from[$i] : '' }}"
                                   data-inputmask="'mask':'99:99','placeholder':'23:59'">
                        </div>
                        <div class="form-group">
                            <label for="inputTo_{{ $i }}" class="bold-400">{{ trans('label.to') }}</label>
                            <input id="inputFrom_{{ $i }}" type="text" class="form-control time-picker"
                                   name="range_to[{{ $i }}]"{{ in_array($i, $available_times) ? '' : ' disabled' }}
                                   value="{{ !empty($available_range_to[$i]) ? $available_range_to[$i] : '' }}"
                                   data-inputmask="'mask':'99:99','placeholder':'23:59'">
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_save') }}</strong></button>
        </div>
    </form>
@endsection