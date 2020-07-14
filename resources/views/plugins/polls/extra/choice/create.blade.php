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
        });
    </script>
@endsection
<form method="post" action="{{ addErrorUrl(addRdrUrl(addExtraUrl('admin/poll-choices', adminUrl('extra')), addExtraUrl('admin/poll-choices', adminUrl('extra')))) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-xs-12">
            <h4 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('polls.choice_lc', 1) }}</h4>
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
                        <label class="required" for="inputPolls">{{ trans_choice('polls.poll', 1) }}</label>
                        <select id="inputPolls" class="form-control select2" name="poll" required
                                data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('polls.poll', 1) }}" style="width: 100%;">
                            @foreach ($polls as $poll)
                                <option value="{{ $poll->id }}"{{ $poll->id == old('poll') ? ' selected' : '' }}>
                                    {{ $poll->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="inputVotes">{{ trans('polls.votes') }}</label>
                    <input class="form-control unsigned-integer-input" id="inputVotes" name="votes" type="text"
                           placeholder="{{ trans('polls.votes') }}" value="{{ old('votes', 0) }}">
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
                        </div><!-- /.tab-pane -->
                    @endforeach
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
            <div class="margin-bottom">
                <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                <div class="pull-right">
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    <a role="button" class="btn btn-warning" href="{{ addExtraUrl('admin/poll-choices', adminUrl('extra')) }}">{{ trans('form.action_cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
