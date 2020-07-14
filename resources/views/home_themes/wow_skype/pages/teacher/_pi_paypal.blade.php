<div id="paypal" class="payment-method">
    <hr>
    <div class="payment-heading">
        <h4>
            <a href="javascript:void(0)" data-toggle="collapse" data-target="#paypal-detail">
                <strong>Paypal</strong>
            </a>
        </h4>
    </div>
    <div id="paypal-detail" class="payment-detail collapse">
        <div class="form-group">
            <label for="inputPaypalEmail">{{ trans('label.paypal_email') }}</label>
            <input type="text" class="form-control" disabled id="inputPaypalEmail" name="paypal_email"
                   value="{{ $payment_paypal['paypal_email'] }}">
        </div>
        <div class="form-group">
            <label for="inputPaypalFullName">{{ trans('label.paypal_full_name') }}</label>
            <input type="text" class="form-control" disabled id="inputPaypalFullName" name="paypal_full_name"
                   value="{{ $payment_paypal['paypal_full_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputPaypalCountry">{{ trans('label.paypal_country') }}</label>
            <select id="inputPaypalCountry" class="form-control" disabled name="paypal_country" style="width: 100%;">
                <option value=""></option>
                {!! countriesAsOptions($payment_paypal['paypal_country']) !!}
            </select>
        </div>
    </div>
</div>