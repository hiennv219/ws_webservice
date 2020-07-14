<div class="form-group">
    <label for="{{ localeInputId('inputContent', $locale) }}">{{ trans('label.content') }}</label>
    <textarea cols="10" rows="10" class="form-control ck-editor" id="{{ localeInputId('inputContent', $locale) }}"
              name="{{ localeInputName('content', $locale) }}"
              placeholder="{{ trans('label.content') }}">{{ $widget->getProperty('content', $locale) }}</textarea>
</div>