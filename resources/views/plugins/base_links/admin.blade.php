@extends('plugins.default_widget.admin')
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
@section('extended_widget_top')
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
        </div>
    </div>
@endsection
