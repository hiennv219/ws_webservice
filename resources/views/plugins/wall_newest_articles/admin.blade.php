@extends('plugins.default_widget.admin')
@section('extended_widget_top')
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="inputNumberOfItems">{{ trans('wall_newest_articles.number_of_items') }}</label>
                <input id="inputNumberOfItems" type="number" class="form-control"
                       name="number_of_items" value="{{ $number_of_items }}">
            </div>
        </div>
    </div>
@endsection