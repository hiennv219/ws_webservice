@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
<form method="post" action="{{ addErrorUrl(addRdrUrl(addExtraUrl('admin/polls', adminUrl('extra')), addExtraUrl('admin/polls', adminUrl('extra')))) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-xs-12">
            <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('polls.poll_lc', 1) }}</h4>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <div class="checkbox icheck">
                    <label for="inputMultiChoice">
                        <input id="inputMultiChoice" name="multi_choice" type="checkbox" value="1">
                        &nbsp; {{ trans('polls.multi_choice') }}
                    </label>
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
                                <label class="required separated" for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                                       name="{{ localeInputName('name', $locale) }}" type="text"
                                       placeholder="{{ trans('label.name') }}" value="{{ oldLocaleInput('name', $locale) }}">
                            </div>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                       name="{{ localeInputName('description', $locale) }}" type="text"
                                       placeholder="{{ trans('label.description') }}" value="{{ oldLocaleInput('description', $locale) }}">
                            </div>
                        </div><!-- /.tab-pane -->
                    @endforeach
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
            <div class="margin-bottom">
                <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                <div class="pull-right">
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    <a role="button" class="btn btn-warning" href="{{ addExtraUrl('admin/polls', adminUrl('extra')) }}">{{ trans('form.action_cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
