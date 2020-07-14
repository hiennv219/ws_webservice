@extends('plugins.default_widget.admin')
@section('extended_widget_top')
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="form-group">
                <label for="inputUsername">{{ trans('instagram_wall.username') }}</label>
                <input id="inputUsername" type="text" class="form-control" name="username" value="{{ $username }}">
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="form-group">
                <label for="inputNumOfItems">{{ trans('instagram_wall.num_of_items') }}</label>
                <input id="inputNumOfItems" type="number" min="1" max="20" class="form-control" name="num_of_items" value="{{ $num_of_items }}">
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="form-group">
                <label for="inputNumOfColumns">{{ trans('instagram_wall.num_of_columns') }}</label>
                <input id="inputNumOfColumns" type="number" min="1" max="6" class="form-control" name="num_of_columns" value="{{ $num_of_columns }}">
            </div>
        </div>
    </div>
@endsection