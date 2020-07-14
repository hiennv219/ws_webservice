@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-payment-information',
    'panel_heading' => trans('label.payment_information'),
])
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('extended_styles')
    <style>
        input[type=checkbox], input[type=radio] {margin-top:2px}
        .payment-heading a {position: relative;z-index: 1}
        .payment-heading label.active {color: #5cb85c;text-decoration: underline}
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            var $inputCountry = $('#inputCountry');

            function showPaymentMethodBasedOnCountry() {
                if ($inputCountry.val() == 'VN') {
                    $('.payment-method:not(#vn)').addClass('hide');
                    $('.payment-method#vn').removeClass('hide');
                }
                else {
                    $('.payment-method:not(#vn)').removeClass('hide');
                    $('.payment-method#vn').addClass('hide');
                }
            }

            showPaymentMethodBasedOnCountry();

            $('.select2').select2({
                theme: 'bootstrap'
            });

            $inputCountry.on('select2:select', function (e) {
                showPaymentMethodBasedOnCountry();
            });

            $('.payment-heading input[type="checkbox"]').on('change', function () {
                var $label = $(this).parent();
                if($(this).is(':checked')) {
                    $label.addClass('active');
                    $label.closest('.payment-method').children('.payment-detail').removeClass('hide');
                }
                else {
                    $label.removeClass('active');
                    $label.closest('.payment-method').children('.payment-detail').addClass('hide');
                }
            });
        });
    </script>
@endsection
@section('profile_content')
    <form method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @include('messages_after_action')
        <div class="form-group">
            <label for="inputCountry" class="control-label">{{ trans('label.country') }}</label>
            <select id="inputCountry" class="form-control select2" name="country" style="width: 100%;" required>
                <option value="">
                    - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                </option>
                {!! countriesAsOptions(isset($payment_info['country']) ? $payment_info['country'] : $auth_user->settings->country) !!}
            </select>
        </div>
        <div id="bank-account" class="payment-method{{ $has_payment_vn ? ' hide' : '' }}">
            <hr>
            <div class="payment-heading">
                <div class="checkbox">
                    <label class="{{ $has_payment_bank_account ? 'active' : '' }}">
                        <input type="checkbox" name="bank_account" value="1"{{ $has_payment_bank_account ? ' checked' : '' }}>
                        <strong>{{ trans('label.bank_account') }}</strong>
                    </label>
                </div>
            </div>
            <div class="payment-detail{{ !$has_payment_bank_account ? ' hide' : '' }}">
                <div class="form-group">
                    <label for="inputBankAccountFullName">{{ trans('label.bank_account_full_name') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountFullName" name="bank_account_full_name"
                           value="{{ $payment_bank_account['bank_account_full_name'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountAddress">{{ trans('label.address') }}</label>
                    <input type="text" class="form-control" id="inputBankAccountAddress" name="bank_account_address"
                           value="{{ $payment_bank_account['bank_account_address'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountCity">{{ trans('label.city') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountCity" name="bank_account_city"
                           value="{{ $payment_bank_account['bank_account_city'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountCountry">{{ trans('label.country') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountCountry" name="bank_account_country"
                           value="{{ $payment_bank_account['bank_account_country'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountRecipientPhoneNumber">{{ trans('label.bank_recipient_phone_number') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountRecipientPhoneNumber" name="bank_account_recipient_phone_number"
                           value="{{ $payment_bank_account['bank_account_recipient_phone_number'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountBankName">{{ trans('label.bank_name') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountBankName" name="bank_account_bank_name"
                           value="{{ $payment_bank_account['bank_account_bank_name'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountSwiftCode">{{ trans('label.bank_swift_code') }}</label>
                    <span><em>({{ trans('label.optional') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountSwiftCode" name="bank_account_swift_code"
                           value="{{ $payment_bank_account['bank_account_swift_code'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountClearingCode">{{ trans('label.bank_clearing_code') }}</label>
                    <span><em>({{ trans('label.optional') }})</em></span>
                    <div class="help-block"><em>({{ trans('label.bank_clearing_code_help') }})</em></div>
                    <input type="text" class="form-control" id="inputBankAccountClearingCode" name="bank_account_clearing_code"
                           value="{{ $payment_bank_account['bank_account_clearing_code'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountNumber">{{ trans('label.bank_account_number') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <div class="help-block"><em>({{ trans('label.bank_account_number_help') }})</em></div>
                    <input type="text" class="form-control" id="inputBankAccountNumber" name="bank_account_number"
                           value="{{ $payment_bank_account['bank_account_number'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountOtherInfo">{{ trans('label.bank_other_info') }}</label>
                    <span><em>({{ trans('label.optional') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountOtherInfo" name="bank_account_other_info"
                           value="{{ $payment_bank_account['bank_account_other_info'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountCurrency">{{ trans('label.bank_currency') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputBankAccountCurrency" name="bank_account_currency"
                           value="{{ $payment_bank_account['bank_account_currency'] }}">
                </div>
                <div class="form-group">
                    <label for="inputBankAccountOwnName">{{ trans('label.bank_own_name') }}</label>
                    <select id="inputBankAccountOwnName" class="form-control" name="bank_account_own_name">
                        <option value=""></option>
                        <option value="1"{{ $payment_bank_account['bank_account_own_name'] == 1 ? ' selected' : '' }}>{{ trans('label.bank_own_name_1') }}</option>
                        <option value="2"{{ $payment_bank_account['bank_account_own_name'] == 2 ? ' selected' : '' }}>{{ trans('label.bank_own_name_2') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="paypal" class="payment-method{{ $has_payment_vn ? ' hide' : '' }}">
            <hr>
            <div class="payment-heading">
                <a href="https://www.paypal.com/" class="pull-right" target="_blank">
                    Paypal.com
                </a>
                <div class="checkbox">
                    <label class="{{ $has_payment_paypal ? 'active' : '' }}">
                        <input type="checkbox" name="paypal" value="1"{{ $has_payment_paypal ? ' checked' : '' }}>
                        <strong>Paypal</strong>
                    </label>
                </div>
            </div>
            <div class="payment-detail{{ !$has_payment_paypal ? ' hide' : '' }}">
                <div class="form-group">
                    <label for="inputPaypalEmail">{{ trans('label.paypal_email') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputPaypalEmail" name="paypal_email"
                           value="{{ $payment_paypal['paypal_email'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPaypalFullName">{{ trans('label.paypal_full_name') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputPaypalFullName" name="paypal_full_name"
                           value="{{ $payment_paypal['paypal_full_name'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPaypalCountry">{{ trans('label.paypal_country') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <select id="inputPaypalCountry" class="form-control select2" name="paypal_country" style="width: 100%;">
                        <option value="">
                            - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                        </option>
                        {!! countriesAsOptions($payment_paypal['paypal_country']) !!}
                    </select>
                </div>
            </div>
        </div>
        <div id="skrill" class="payment-method{{ $has_payment_vn ? ' hide' : '' }}">
            <hr>
            <div class="payment-heading">
                <a href="https://www.skrill.com/" class="pull-right" target="_blank">
                    Skrill.com
                </a>
                <div class="checkbox">
                    <label class="{{ $has_payment_skrill ? 'active' : '' }}">
                        <input type="checkbox" name="skrill" value="1"{{ $has_payment_skrill ? ' checked' : '' }}>
                        <strong>Skrill</strong>
                    </label>
                </div>
            </div>
            <div class="payment-detail{{ !$has_payment_skrill ? ' hide' : '' }}">
                <div class="form-group">
                    <label for="inputSkrillEmail">{{ trans('label.skrill_email') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputSkrillEmail" name="skrill_email"
                           value="{{ $payment_skrill['skrill_email'] }}">
                </div>
                <div class="form-group">
                    <label for="inputSkrillFullName">{{ trans('label.skrill_full_name') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputSkrillFullName" name="skrill_full_name"
                           value="{{ $payment_skrill['skrill_full_name'] }}">
                </div>
                <div class="form-group">
                    <label for="inputSkrillCountry">{{ trans('label.skrill_country') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <select id="inputSkrillCountry" class="form-control select2" name="skrill_country" style="width: 100%;">
                        <option value="">
                            - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                        </option>
                        {!! countriesAsOptions($payment_skrill['skrill_country']) !!}
                    </select>
                </div>
            </div>
        </div>
        <div id="payoneer" class="payment-method{{ $has_payment_vn ? ' hide' : '' }}">
            <hr>
            <div class="payment-heading">
                <a href="https://www.payoneer.com/" class="pull-right" target="_blank">
                    Payoneer.com
                </a>
                <div class="checkbox">
                    <label class="{{ $has_payment_payoneer ? 'active' : '' }}">
                        <input type="checkbox" name="payoneer" value="1"{{ $has_payment_payoneer ? ' checked' : '' }}>
                        <strong>Payoneer</strong>
                    </label>
                </div>
            </div>
            <div class="payment-detail{{ !$has_payment_payoneer ? ' hide' : '' }}">
                <div class="form-group">
                    <label for="inputPayoneerBenificiaryName">{{ trans('label.payoneer_benificiary_name') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputPayoneerBenificiaryName" name="payoneer_benificiary_name"
                           value="{{ $payment_payoneer['payoneer_benificiary_name'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPayoneerAddress">{{ trans('label.address') }}</label>
                    <input type="text" class="form-control" id="inputPayoneerAddress" name="payoneer_address"
                           value="{{ $payment_payoneer['payoneer_address'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPayoneerBankName">{{ trans('label.bank_name') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputPayoneerBankName" name="payoneer_bank_name"
                           value="{{ $payment_payoneer['payoneer_bank_name'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPayoneerCountry">{{ trans('label.country') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <select class="form-control" name="payoneer_country" id="inputPayoneerCountry">
                        <option value="US"{{ $payment_payoneer['payoneer_country'] == 'US' ? ' selected' : '' }}>United States</option>
                        <option value="DE"{{ $payment_payoneer['payoneer_country'] == 'DE' ? ' selected' : '' }}>Germany</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputPayoneerClearingCode">{{ trans('label.bank_clearing_code') }}</label>
                    <div class="help-block"><em>({{ trans('label.bank_clearing_code_help') }})</em></div>
                    <input type="text" class="form-control" id="inputPayoneerClearingCode" name="payoneer_clearing_code"
                           value="{{ $payment_payoneer['payoneer_clearing_code'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPayoneerAccountNumber">{{ trans('label.payoneer_account_number') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <input type="text" class="form-control" id="inputPayoneerAccountNumber" name="payoneer_account_number"
                           value="{{ $payment_payoneer['payoneer_account_number'] }}">
                </div>
                <div class="form-group">
                    <label for="inputPayoneerAccountCurrency">{{ trans('label.bank_currency') }}</label>
                    <span><em>({{ trans('label.required') }})</em></span>
                    <select class="form-control" name="payoneer_currency" id="inputPayoneerAccountCurrency">
                        <option value="USD"{{ $payment_payoneer['payoneer_currency'] == 'USD' ? ' selected' : '' }}>USD</option>
                        <option value="EUR"{{ $payment_payoneer['payoneer_currency'] == 'EUR' ? ' selected' : '' }}>EUR</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputPayoneerOtherInfo">{{ trans('label.bank_other_info') }}</label>
                    <span><em>({{ trans('label.optional') }})</em></span>
                    <input type="text" class="form-control" id="inputPayoneerOtherInfo" name="payoneer_other_info"
                           value="{{ $payment_payoneer['payoneer_other_info'] }}">
                </div>
            </div>
        </div>
        <div id="others" class="payment-method{{ $has_payment_vn ? ' hide' : '' }}">
            <hr>
            <div class="payment-heading">
                <div class="checkbox">
                    <label class="{{ $has_payment_others ? 'active' : '' }}">
                        <input type="checkbox" name="others" value="1"{{ $has_payment_others ? ' checked' : '' }}>
                        <strong>{{ trans('label.other_payment_methods') }}</strong>
                    </label>
                </div>
            </div>
            <div class="payment-detail{{ !$has_payment_others ? ' hide' : '' }}">
                <div class="form-group">
                    <label for="inputOthersContent" class="sr-only">{{ trans('label.content') }}</label>
                    <div class="help-block"><em>{{ trans('label.other_payment_methods_help') }}</em></div>
                    <textarea name="others_content" id="inputOthersContent" rows="5"
                              class="form-control">{{ $payment_others['others_content'] }}</textarea>
                </div>
            </div>
        </div>
        <div id="vn" class="payment-method{{ !$has_payment_vn ? ' hide' : '' }}">
            <hr>
            <div class="form-group">
                <label for="inputVnAccountNumber">{{ trans('label.vn_account_number') }}</label>
                <span><em>({{ trans('label.required') }})</em></span>
                <div class="help-block"><em>({{ trans('label.vn_account_number_help') }})</em></div>
                <input type="text" class="form-control" id="inputVnAccountNumber" name="vn_account_number"
                       value="{{ $payment_vn['vn_account_number'] }}">
            </div>
            <div class="form-group">
                <label for="inputVnBankName">{{ trans('label.bank_name') }}</label>
                <span><em>({{ trans('label.required') }})</em></span>
                <input type="text" class="form-control" id="inputVnBankName" name="vn_bank_name"
                       value="{{ $payment_vn['vn_bank_name'] }}">
            </div>
            <div class="form-group">
                <label for="inputVnAccountName">{{ trans('label.vn_account_name') }}</label>
                <input type="text" class="form-control" id="inputVnAccountName" name="vn_account_name"
                       value="{{ $payment_vn['vn_account_name'] }}">
            </div>
            <div class="form-group">
                <label for="inputVnCity">{{ trans('label.vn_city') }}</label>
                <span><em>({{ trans('label.required') }})</em></span>
                <input type="text" class="form-control" id="inputVnCity" name="vn_city"
                       value="{{ $payment_vn['vn_city'] }}">
            </div>
            <div class="form-group">
                <label for="inputVnBranch">{{ trans('label.vn_branch') }}</label>
                <span><em>({{ trans('label.required') }})</em></span>
                <input type="text" class="form-control" id="inputVnCity" name="vn_branch"
                       value="{{ $payment_vn['vn_branch'] }}">
            </div>
            <div class="form-group">
                <label for="inputVnAccountOwnName">{{ trans('label.bank_own_name') }}</label>
                <select id="inputVnAccountOwnName" class="form-control" name="vn_account_own_name">
                    <option value=""></option>
                    <option value="1"{{ $payment_vn['vn_account_own_name'] == 1 ? ' selected' : '' }}>{{ trans('label.bank_own_name_1') }}</option>
                    <option value="2"{{ $payment_vn['vn_account_own_name'] == 2 ? ' selected' : '' }}>{{ trans('label.bank_own_name_2') }}</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_save') }}</strong></button>
        </div>
    </form>
@endsection