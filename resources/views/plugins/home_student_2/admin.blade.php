@extends('plugins.default_widget.admin')
@section('extended_scripts')
    @parent
    @include('file_manager.open_documents_script')
@endsection
@section('extended_widget_bottom')
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="inputVideoUrl1">Video URL 1</label>
                <input class="form-control" id="inputVideoUrl1"
                       name="video_url_2" type="text"
                       placeholder="Video URL 1" value="{{ $video_url_1 }}">
            </div>
            <div class="form-group">
                <label for="inputVideoUrl2">Video URL 2</label>
                <input class="form-control" id="inputVideoUrl2"
                       name="video_url_1" type="text"
                       placeholder="Video URL 2" value="{{ $video_url_2 }}">
            </div>
        </div>
    </div>
@endsection