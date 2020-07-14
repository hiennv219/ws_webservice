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
                <h5>Tawk.to chatbox service</h5>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label for="inputChatboxEnable">
                            <input id="inputchatEnable" type="checkbox" name="chatbox_enable"
                                   value="1"{{ $chatbox_enable ? ' checked' : '' }}>
                            &nbsp; {{ trans('tawkto_chatbox_services.enable') }}
                        </label>
                    </div>
                    <div class="checkbox icheck">
                        <label for="inputChatboxAsync">
                            <input id="inputchatAsync" type="checkbox" name="chatbox_async"
                                   value="1"{{ $chatbox_async ? ' checked' : '' }}>
                            &nbsp; {{ trans('tawkto_chatbox_services.async') }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="chatbox_id">Chatbox ID</label>
                    <input id="chatbox_id" type="text" class="form-control" name="chatbox_id" value="{{ $chatbox_id }}"
                           placeholder="{{ trans('tawkto_chatbox_services.tawkto_chatbox_id') }}">
                    <p>{!! trans('tawkto_chatbox_services.chatbox_id_guide', ['url' => 'http://dashboard.tawk.to']) !!}</p>
                </div>

            </div>
            <div class="col-xs-12 col-md-4">
                <h5>{{ trans('tawkto_chatbox_services.example') }}</h5>
                <div class="row">
                    <div class="highlight">
                        <pre>
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/54f6736dbd5fa428704c651a/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
                        </pre>
                    </div>
                    <span class="text-danger">54f6736dbd5fa428704c651a</span> {{ trans('tawkto_chatbox_services.chatbox_id_example_guide') }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="checkbox icheck">
        <label for="inputCacheEnable">
            <input id="inputCacheEnable" type="checkbox" name="cache_enable"
                   value="1"{{ $cache_enable ? ' checked' : '' }}>
            &nbsp; {{ trans('tawkto_chatbox_services.cache_enable') }}
        </label>
    </div>
    <div class="help-block">{{ trans('tawkto_chatbox_services.cache_enable_help') }}</div>
</div>
