<div id="payoneer" class="payment-method">
    <hr>
    <div class="payment-heading">
        <h4>
            <a href="javascript:void(0)" data-toggle="collapse" data-target="#payoneer-detail">
                <strong>Payoneer</strong>
            </a>
        </h4>
    </div>
    <div id="payoneer-detail" class="payment-detail collapse">
        <div class="form-group">
            <label for="inputPayoneerBenificiaryName">{{ trans('label.payoneer_benificiary_name') }}</label>
            <input type="text" class="form-control" disabled id="inputPayoneerBenificiaryName" name="payoneer_benificiary_name"
                   value="{{ $payment_payoneer['payoneer_benificiary_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputPayoneerAddress">{{ trans('label.address') }}</label>
            <input type="text" class="form-control" disabled id="inputPayoneerAddress" name="payoneer_address"
                   value="{{ $payment_payoneer['payoneer_address'] }}">
        </div>
        <div class="form-group">
            <label for="inputPayoneerBankName">{{ trans('label.bank_name') }}</label>
            <input type="text" class="form-control" disabled id="inputPayoneerBankName" name="payoneer_bank_name"
                   value="{{ $payment_payoneer['payoneer_bank_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputPayoneerCountry">{{ trans('label.country') }}</label>
            <select class="form-control" name="payoneer_country" disabled id="inputPayoneerCountry">
                <option value="US"{{ $payment_payoneer['payoneer_country'] == 'US' ? ' selected' : '' }}>United States</option>
                <option value="DE"{{ $payment_payoneer['payoneer_country'] == 'DE' ? ' selected' : '' }}>Germany</option>
            </select>
        </div>
        <div class="form-group">
            <label for="inputPayoneerClearingCode">{{ trans('label.bank_clearing_code') }}</label>
            <input type="text" class="form-control" disabled id="inputPayoneerClearingCode" name="payoneer_clearing_code"
                   value="{{ $payment_payoneer['payoneer_clearing_code'] }}">
        </div>
        <div class="form-group">
            <label for="inputPayoneerAccountNumber">{{ trans('label.payoneer_account_number') }}</label>
            <input type="text" class="form-control" disabled id="inputPayoneerAccountNumber" name="payoneer_account_number"
                   value="{{ $payment_payoneer['payoneer_account_number'] }}">
        </div>
        <div class="form-group">
            <label for="inputPayoneerAccountCurrency">{{ trans('label.bank_currency') }}</label>
            <select class="form-control" disabled name="payoneer_currency" id="inputPayoneerAccountCurrency">
                <option value="USD"{{ $payment_payoneer['payoneer_currency'] == 'USD' ? ' selected' : '' }}>USD</option>
                <option value="EUR"{{ $payment_payoneer['payoneer_currency'] == 'EUR' ? ' selected' : '' }}>EUR</option>
            </select>
        </div>
        <div class="form-group">
            <label for="inputPayoneerOtherInfo">{{ trans('label.bank_other_info') }}</label>
            <input type="text" class="form-control" disabled id="inputPayoneerOtherInfo" name="payoneer_other_info"
                   value="{{ $payment_payoneer['payoneer_other_info'] }}">
        </div>
    </div>
</div>