@section('extended_scripts')
    @parent
    @include('file_manager.open_documents_script')
@endsection

<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="inputUrl">{{ trans('label.picture') }} {{ trans('label.or_lc') }} Video URL (Youtube, Vimeo, Dailymotion)</label>
                    <div class="input-group">
                        <input class="form-control" id="inputUrl" name="url"
                               placeholder="{{ trans('label.picture') }} {{ trans('label.or_lc') }} Video URL (Youtube, Vimeo, Dailymotion)" type="text" value="{{ $url }}">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary image-from-documents"
                                    data-input-id="inputUrl">
                                <i class="fa fa-server"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
