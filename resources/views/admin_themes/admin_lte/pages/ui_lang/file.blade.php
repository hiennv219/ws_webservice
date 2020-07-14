<form method="post" action="{{ $form_action }}">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <input type="hidden" name="file_locale" value="{{ $file_locale }}">
    <input type="hidden" name="file_name" value="{{ $file_name }}">
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('form.action_edit') }} - {{ trans_choice('label.file', 1) }} <em>{{ $file_locale . '/' .$file_name }}</em>
                    </h3>
                </div>
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if (session('response'))
                        <div class="alert alert-success">
                            {{ session('response') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="inputFileContent">{{ trans('label.content') }}</label>
                        <textarea rows="20" class="form-control code-editor" id="inputFileContent" name="file_content" placeholder="{{ trans('label.content') }}">{{ $file_content }}</textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('form.list_of', ['name' => trans_choice('label.file_lc', 2)]) }}
                    </h3>
                </div>
                <div class="box-body">
                    @foreach($files as $locale => $file_names)
                        <h4>{{ $locale }}</h4>
                        <ul>
                            @foreach($file_names as $item)
                                <li><a href="?file_locale={{ $item['locale'] }}&file_name={{ $item['file'] }}">{{ $item['file'] }}</a></li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>