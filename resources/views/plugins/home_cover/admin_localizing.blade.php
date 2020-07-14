<div class="form-group">
    <label for="{{ localeInputId('inputContent', $locale) }}">{{ trans('label.content') }}</label>
    <textarea cols="10" rows="10" class="form-control" id="{{ localeInputId('inputContent', $locale) }}"
              name="{{ localeInputName('content', $locale) }}"
              placeholder="{{ trans('label.content') }}">{{ $widget->getProperty('content', $locale) }}</textarea>
</div>
<div class="form-group">
    <label for="{{ localeInputId('inputLink', $locale) }}">{{ trans_choice('label.link', 1) }}</label>
    <input type="text" class="form-control" id="{{ localeInputId('inputLink', $locale) }}"
              name="{{ localeInputName('link', $locale) }}"
              placeholder="{{ trans_choice('label.link', 1) }}" value="{{ $widget->getProperty('link', $locale) }}">
</div>