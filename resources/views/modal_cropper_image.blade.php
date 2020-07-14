<link rel="stylesheet" href="{{ libraryAsset('modal_cropper_image.css') }}">
<div class="modal fade" id="cropper-image-modal" aria-hidden="true" aria-labelledby="cropper-image-modal-label" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="cropper-image-form" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="cropper-image-modal-label">{{ trans('form.action_choose') }} {{ trans('label.image_lc') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="cropper-image-body">
                        <!-- Upload image and data -->
                        <div class="cropper-image-upload">
                            <input type="hidden" class="cropper-image-src" name="cropper_image_src">
                            <input type="hidden" class="cropper-image-data" name="cropper_image_data">
                            <label for="inputCropImage">{{ trans('form.action_upload') }}:</label>
                            <input type="file" class="cropper-image-input" id="inputCropImage" name="cropper_image_file">
                        </div>
                        <div class="help-block cropper-image-help-block">{{ trans('label.max_upload_file_size', ['size' => asKb($max_upload_file_size)]) }}</div>
                        <!-- Crop and preview -->
                        <div class="row">
                            <div class="col-md-9">
                                <div class="cropper-image-wrapper"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="cropper-image-preview preview-lg"></div>
                                <div class="cropper-image-preview preview-md"></div>
                                <div class="cropper-image-preview preview-sm"></div>
                            </div>
                        </div>
                        <div class="row cropper-image-buttons">
                            <div class="col-md-9">
                                <div class="btn-group wp">
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="zoom" data-option="0.2">
                                        <i class="fa fa-search-plus"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="zoom" data-option="-0.2">
                                        <i class="fa fa-search-minus"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="setDragMode" data-option="move">
                                        <i class="fa fa-arrows"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="setDragMode" data-option="crop">
                                        <i class="fa fa-crop"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="scaleX" data-option="-1">
                                        <i class="fa fa-arrows-h"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="scaleY" data-option="-1">
                                        <i class="fa fa-arrows-v"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="clear">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="reset">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                                <div class="btn-group wp">
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="rotate" data-option="-90">
                                        <i class="fa fa-rotate-left"></i> 90&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="rotate" data-option="-15">
                                        <i class="fa fa-rotate-left"></i> 15&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="rotate" data-option="-30">
                                        <i class="fa fa-rotate-left"></i> 30&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="rotate" data-option="-45">
                                        <i class="fa fa-rotate-left"></i> 45&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="rotate" data-option="45">
                                        <i class="fa fa-rotate-right"></i> 45&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="rotate" data-option="30">
                                        <i class="fa fa-rotate-right"></i> 30&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 hidden-xs" data-method="rotate" data-option="15">
                                        <i class="fa fa-rotate-right"></i> 15&deg;
                                    </button>
                                    <button type="button" class="btn bg-purple wp-sm-8 wp-xs-4" data-method="rotate" data-option="90">
                                        <i class="fa fa-rotate-right"></i> 90&deg;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary cropper-image-save">{{ trans('form.action_save') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="cropper-image-loading" aria-label="Loading" role="img" tabindex="-1"></div>
<script src="{{ libraryAsset('modal_cropper_image.js') }}"></script>