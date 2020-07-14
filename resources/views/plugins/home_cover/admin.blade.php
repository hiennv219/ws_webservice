@extends('plugins.default_widget.admin')
@section('extended_scripts')
    @parent
    @include('file_manager.open_documents_script')
@endsection
@section('extended_widget_top')
    <div class="row">
        <div class="col-xs-12">
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
            <div class="form-group">
                <label for="inputVideoUrl">Video URL</label>
                <input class="form-control" id="inputVideoUrl"
                       name="video_url" type="text"
                       placeholder="Video URL" value="{{ $video_url }}">
            </div>
        </div>
    </div>
@endsection