@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $('.select2').select2();
        });
    </script>
@endsection

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('app_settings.short_code') }}</h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputShortCodeEnable">
                    <input id="inputShortCodeEnable" type="checkbox" name="short_code_enable"
                           value="1"{{ $short_code_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('app_settings.short_code_enable') }}
                </label>
            </div>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('app_settings.posts') }}</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="inputDefaultArticleCategory">{{ trans('app_settings.default_article_category') }}</label>
                    <select id="inputDefaultArticleCategory" class="form-control select2" name="default_article_category" style="width: 100%">
                        @foreach ($article_categories as $category)
                            <option value="{{ $category->id }}"{{ $category->id == $default_article_category ? ' selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="help-block">
                        <small>
                            <a href="{{ adminUrl('article-categories/{id}/edit', ['id' => $default_article_category]) }}">
                                {{ trans('form.action_edit') }}
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>