<div id="others" class="payment-method">
    <hr>
    <div class="payment-heading">
        <h4>
            <a href="javascript:void(0)" data-toggle="collapse" data-target="#others-detail">
                <strong>{{ trans('label.other_payment_methods') }}</strong>
            </a>
        </h4>
    </div>
    <div id="others-detail" class="payment-detail collapse">
        <div class="form-group">
            <label for="inputOthersContent" class="sr-only">{{ trans('label.content') }}</label>
            <textarea name="others_content" id="inputOthersContent" rows="5" disabled
                      class="form-control">{{ $payment_others['others_content'] }}</textarea>
        </div>
    </div>
</div>