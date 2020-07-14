<div id="bank-account-vn" class="payment-method">
    <hr>
    <div class="payment-heading">
        <h4>
            <a href="javascript:void(0)" data-toggle="collapse" data-target="#bank-account-vn-detail">
                <strong>{{ trans('label.bank_account') }} (Vietnam)</strong>
            </a>
        </h4>
    </div>
    <div id="bank-account-vn-detail" class="payment-detail collapse">
        <div class="form-group">
            <label for="inputVnAccountNumber">{{ trans('label.vn_account_number') }}</label>
            <input type="text" class="form-control" disabled id="inputVnAccountNumber" name="vn_account_number"
                   value="{{ $payment_vn['vn_account_number'] }}">
        </div>
        <div class="form-group">
            <label for="inputVnBankName">{{ trans('label.bank_name') }}</label>
            <input type="text" class="form-control" disabled id="inputVnBankName" name="vn_bank_name"
                   value="{{ $payment_vn['vn_bank_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputVnAccountName">{{ trans('label.vn_account_name') }}</label>
            <input type="text" class="form-control" disabled id="inputVnAccountName" name="vn_account_name"
                   value="{{ $payment_vn['vn_account_name'] }}">
        </div>
        <div class="form-group">
            <label for="inputVnCity">{{ trans('label.vn_city') }}</label>
            <input type="text" class="form-control" disabled id="inputVnCity" name="vn_city"
                   value="{{ $payment_vn['vn_city'] }}">
        </div>
        <div class="form-group">
            <label for="inputVnBranch">{{ trans('label.vn_branch') }}</label>
            <input type="text" class="form-control" disabled id="inputVnCity" name="vn_branch"
                   value="{{ $payment_vn['vn_branch'] }}">
        </div>
        <div class="form-group">
            <label for="inputVnAccountOwnName">{{ trans('label.bank_own_name') }}</label>
            <select id="inputVnAccountOwnName" class="form-control" disabled name="vn_account_own_name">
                <option value=""></option>
                <option value="1"{{ $payment_vn['vn_account_own_name'] == 1 ? ' selected' : '' }}>{{ trans('label.bank_own_name_1') }}</option>
                <option value="2"{{ $payment_vn['vn_account_own_name'] == 2 ? ' selected' : '' }}>{{ trans('label.bank_own_name_2') }}</option>
            </select>
        </div>
    </div>
</div>