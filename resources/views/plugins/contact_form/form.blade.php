<form action="{{ addRdrUrl(addExtraUrl('contact-forms', homeUrl('extra'))) }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="error_bag" value="{{ $error_bag }}">
    @if (count($errors->{$error_bag}) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->{$error_bag}->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="form-group">
        <label for="inputFullName">{{ trans('label.full_name') }}</label>
        <input id="inputFullName" type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
    </div>
    <div class="form-group">
        <label for="inputAddress">{{ trans('label.address') }}</label>
        <input id="inputAddress" type="text" name="address" class="form-control" value="{{ old('address') }}">
    </div>
    <div class="form-group">
        <label for="inputPhone">{{ trans('label.phone') }}</label>
        <input id="inputPhone" type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>
    <div class="form-group">
        <label for="inputEmail">{{ trans('label.email') }}</label>
        <input id="inputEmail" type="text" name="email" class="form-control" value="{{ old('email') }}">
    </div>
    <div class="form-group">
        <label for="inputWebsite">{{ trans('label.website') }}</label>
        <input id="inputWebsite" type="text" name="website" class="form-control" value="{{ old('website') }}">
    </div>
    <div class="form-group">
        <label for="inputMessage">{{ trans('label.content') }}</label>
        <textarea id="inputMessage" cols="10" rows="5" name="message" class="form-control">{{ old('message') }}</textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('form.action_send') }}</button>
    </div>
</form>