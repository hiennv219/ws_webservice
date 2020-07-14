@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection

<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <h5>Google Analytics</h5>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label for="inputGaEnable">
                            <input id="inputGaEnable" type="checkbox" name="ga_enable" value="1"{{ $ga_enable ? ' checked' : '' }}>
                            &nbsp; {{ trans('analytic_services.enable') }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputGaId">ID</label>
                    <input id="inputGaId" type="text" class="form-control" name="ga_id" value="{{ $ga_id }}">
                </div>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label for="inputGaAsync">
                            <input id="inputGaAsync" type="checkbox" name="ga_async" value="1"{{ $ga_async ? ' checked' : '' }}>
                            &nbsp; {{ trans('analytic_services.ga_async') }}
                        </label>
                    </div>
                    <div class="help-block">{{ trans('analytic_services.ga_async_help') }}</div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <h5>MixPanel</h5>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label for="inputMixPanelEnable">
                            <input id="inputMixPanelEnable" type="checkbox" name="mix_panel_enable" value="1"{{ $mix_panel_enable ? ' checked' : '' }}>
                            &nbsp; {{ trans('analytic_services.enable') }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMixPanelToken">Token</label>
                    <input id="inputMixPanelToken" type="text" class="form-control" name="mix_panel_token" value="{{ $mix_panel_token }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="checkbox icheck">
        <label for="inputCacheEnable">
            <input id="inputCacheEnable" type="checkbox" name="cache_enable" value="1"{{ $cache_enable ? ' checked' : '' }}>
            &nbsp; {{ trans('analytic_services.cache_enable') }}
        </label>
    </div>
    <div class="help-block">{{ trans('analytic_services.cache_enable_help') }}</div>
</div>