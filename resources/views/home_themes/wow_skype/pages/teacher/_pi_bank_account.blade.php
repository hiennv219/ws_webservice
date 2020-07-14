<div id="bank-account" class="payment-method">
    <hr>
    <div class="payment-heading">
        <h4>
            <a href="javascript:void(0)" data-toggle="collapse" data-target="#bank-account-detail">
                <strong>{{ trans('label.bank_account') }}</strong>
            </a>
        </h4>
    </div>
    <div id="bank-account-detail" class="payment-detail collapse">
        <div class="form-group">
            <label for="inputBankAccountFullName">{{ trans('label.bank_account_full_name') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountFullName" name="bank_account_full_name"
                   value="{{ $payment_bank_account['bank_account_full_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountAddress">{{ trans('label.address') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountAddress" name="bank_account_address"
                   value="{{ $payment_bank_account['bank_account_address'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountCity">{{ trans('label.city') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountCity" name="bank_account_city"
                   value="{{ $payment_bank_account['bank_account_city'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountCountry">{{ trans('label.country') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountCountry" name="bank_account_country"
                   value="{{ $payment_bank_account['bank_account_country'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountRecipientPhoneNumber">{{ trans('label.bank_recipient_phone_number') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountRecipientPhoneNumber" name="bank_account_recipient_phone_number"
                   value="{{ $payment_bank_account['bank_account_recipient_phone_number'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountBankName">{{ trans('label.bank_name') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountBankName" name="bank_account_bank_name"
                   value="{{ $payment_bank_account['bank_account_bank_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountSwiftCode">{{ trans('label.bank_swift_code') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountSwiftCode" name="bank_account_swift_code"
                   value="{{ $payment_bank_account['bank_account_swift_code'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountClearingCode">{{ trans('label.bank_clearing_code') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountClearingCode" name="bank_account_clearing_code"
                   value="{{ $payment_bank_account['bank_account_clearing_code'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountNumber">{{ trans('label.bank_account_number') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountNumber" name="bank_account_number"
                   value="{{ $payment_bank_account['bank_account_number'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountOtherInfo">{{ trans('label.bank_other_info') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountOtherInfo" name="bank_account_other_info"
                   value="{{ $payment_bank_account['bank_account_other_info'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountCurrency">{{ trans('label.bank_currency') }}</label>
            <input type="text" class="form-control" disabled id="inputBankAccountCurrency" name="bank_account_currency"
                   value="{{ $payment_bank_account['bank_account_currency'] }}">
        </div>
        <div class="form-group">
            <label for="inputBankAccountOwnName">{{ trans('label.bank_own_name') }}</label>
            <select id="inputBankAccountOwnName" class="form-control" disabled name="bank_account_own_name">
                <option value=""></option>
                <option value="1"{{ $payment_bank_account['bank_account_own_name'] == 1 ? ' selected' : '' }}>{{ trans('label.bank_own_name_1') }}</option>
                <option value="2"{{ $payment_bank_account['bank_account_own_name'] == 2 ? ' selected' : '' }}>{{ trans('label.bank_own_name_2') }}</option>
            </select>
        </div>
    </div>
</div>