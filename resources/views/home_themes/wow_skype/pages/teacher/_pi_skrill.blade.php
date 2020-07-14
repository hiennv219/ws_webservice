<div id="skrill" class="payment-method">
    <hr>
    <div class="payment-heading">
        <h4>
            <a href="javascript:void(0)" data-toggle="collapse" data-target="#skrill-detail">
                <strong>Skrill</strong>
            </a>
        </h4>
    </div>
    <div id="skrill-detail" class="payment-detail collapse">
        <div class="form-group">
            <label for="inputSkrillEmail">{{ trans('label.skrill_email') }}</label>
            <input type="text" class="form-control" disabled id="inputSkrillEmail" name="skrill_email"
                   value="{{ $payment_skrill['skrill_email'] }}">
        </div>
        <div class="form-group">
            <label for="inputSkrillFullName">{{ trans('label.skrill_full_name') }}</label>
            <input type="text" class="form-control" disabled id="inputSkrillFullName" name="skrill_full_name"
                   value="{{ $payment_skrill['skrill_full_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputSkrillCountry">{{ trans('label.skrill_country') }}</label>
            <select id="inputSkrillCountry" class="form-control" disabled name="skrill_country" style="width: 100%;">
                {!! countriesAsOptions($payment_skrill['skrill_country']) !!}
            </select>
        </div>
    </div>
</div>