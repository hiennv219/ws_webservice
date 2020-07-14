@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/inputmask.binding.js') }}"></script>
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
    <div class="box-header">
        <h3 class="box-title">
            {{ trans_choice('currency_exchange.exchange_rate',2) }}
        </h3>
    </div>
    <div class="box-body form-horizontal">
        @foreach($currencies as $currencyCode => $currency)
            <div class="form-group">
                <label for="inputExchange_{{ $currencyCode }}" class="col-sm-3 control-label">{{ $currency['name'] }}
                    ({{ $currency['symbol'] }})</label>
                <div class="col-sm-3">
                    <input id="inputExchange_{{ $currencyCode }}" class="form-control" type="text"
                           name="exchange_rates[{{ $currencyCode }}]"
                           data-inputmask="'alias':'decimal','radixPoint':'{{ $number_format_chars[0] }}','groupSeparator':'{{ $number_format_chars[1] }}','autoGroup':true,'integerDigits':17,'digits':2,'digitsOptional':false,'placeholder':'0{{ $number_format_chars[0] }}00'"
                           value="{{ toFormattedNumber($exchange_rates[$currencyCode]) }}"{{ $main_currency_code == $currencyCode ? ' disabled' : '' }}>
                </div>
                <div class="col-sm-6">
                    <div class="control-label pull-left">
                        @if($main_currency_code != $currencyCode)
                            <em>({{ toFormattedNumber(2*$exchange_rates[$currencyCode]) }} {{ $currencyCode }} = {{ toFormattedCurrency(2*$exchange_rates[$currencyCode], $currencyCode) }})</em>
                        @else
                            {{ trans('currency_exchange.main_currency') }} (<em>{!! trans('currency_exchange.change_here', ['url' => meUrl('settings')]) !!})</em>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>