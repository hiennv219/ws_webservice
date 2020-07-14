@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('.select2').select2();
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $('#inputShowArticles').on('ifChecked', function (e) {
                $('#not-show-articles').addClass('hide');
                $('#show-articles').removeClass('hide');
            }).on('ifUnchecked', function (e) {
                $('#not-show-articles').removeClass('hide');
                $('#show-articles').addClass('hide');
            });
        });
    </script>
@endsection

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <label for="inputCategory">{{ trans_choice('label.category', 1) }}</label>
            <select id="inputCategory" class="form-control select2" name="category_id" style="width: 100%;">
                <option value="0">[{{ trans('label.not_set') }}]</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"{{ $category_id == $category->id ? ' selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputShowArticles">
                    <input id="inputShowArticles" type="checkbox" name="show_articles"
                           value="1"{{ $show_articles == 1 ? ' checked' : '' }}>
                    &nbsp; {{ trans('article_category.show_articles') }}
                </label>
            </div>
        </div>
        <div id="show-articles" class="{{ $show_articles == 1 ? '' : 'hide' }}">
            <div class="form-group">
                <label for="inputNumberOfItems">{{ trans('article_category.number_of_items') }}</label>
                <input id="inputNumberOfItems" type="number" class="form-control"
                       name="number_of_items" value="{{ $number_of_items }}">
            </div>
        </div>
        <div id="not-show-articles" class="{{ $show_articles == 1 ? 'hide' : '' }}">
            <div class="form-group">
                <label for="inputImage">{{ trans('label.picture') }}</label>
                <div class="input-group">
                    <input class="form-control" id="inputImage" name="image"
                           placeholder="{{ trans('label.picture') }}" type="text" value="{{ $image }}">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary image-from-documents"
                                data-input-id="inputImage">
                            <i class="fa fa-server"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputShowButton">
                    <input id="inputShowButton" type="checkbox" name="show_button"
                           value="1"{{ $show_button == 1 ? ' checked' : '' }}>
                    &nbsp; {{ trans('article_category.show_button') }}
                </label>
            </div>
        </div>
    </div>
</div>
