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
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
<form method="post" action="{{ addRdrUrl(addExtraUrl('admin/polls/id', adminUrl('extra')) . '&id=' . $poll->id) }}">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-warning delete" href="{{ addErrorUrl(addRdrUrl(addExtraUrl('admin/polls/id', adminUrl('extra')) . '&id=' . $poll->id, addExtraUrl('admin/polls', adminUrl('extra')))) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a class="btn btn-success" href="{{ addExtraUrl('admin/polls/id/sort', adminUrl('extra')) . '&id=' . $poll->id }}">
                    {{ trans('form.action_sort') }}
                </a>
                <a class="btn btn-primary pull-right" href="{{ addExtraUrl('admin/polls/create', adminUrl('extra')) }}">
                    {{ trans('form.action_add') }} {{ trans_choice('polls.poll', 1) }}
                </a>
            </div>
            <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('polls.poll_lc', 1) }}</h4>
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
                        <input id="inputMultiChoice" name="multi_choice" type="checkbox" value="1"{{ $poll->multi_choice ? ' checked' : '' }}>
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
                            <?php
                                $trans = $poll->translate($locale);
                                $name = $trans ? $trans->name : '';
                                $description = $trans ? $trans->description : '';
                            ?>
                            <div class="form-group">
                                <label class="required separated" for="{{ localeInputId('inputName', $locale) }}">{{ trans('label.name') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputName', $locale) }}"
                                       name="{{ localeInputName('name', $locale) }}" type="text"
                                       placeholder="{{ trans('label.name') }}" value="{{ $name }}">
                            </div>
                            <div class="form-group">
                                <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                       name="{{ localeInputName('description', $locale) }}" type="text"
                                       placeholder="{{ trans('label.description') }}" value="{{ $description }}">
                            </div>
                        </div><!-- /.tab-pane -->
                    @endforeach
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
            <div class="margin-bottom">
                <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                <div class="pull-right">
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    <a role="button" class="btn btn-warning" href="{{ addExtraUrl('admin/polls', adminUrl('extra')) }}">{{ trans('form.action_cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
