@foreach($user_certificates as $certificate)
    <form id="certificate-{{ $certificate->id }}" method="post" action="{{ homeUrl('profile/user-certificates/{id}', ['id' => $certificate->id]) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @if (count($errors->{'certificate_' . $certificate->id}) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->{'certificate_' . $certificate->id}->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label for="inputFreshCertificateType-{{ $certificate->id }}" class="control-label">{{ trans('label.certificate_type') }}</label>
            <select class="form-control inputFreshCertificateType" id="inputFreshCertificateType-{{ $certificate->id }}" name="type" required>
                <option value=""></option>
                @foreach($certificate_types as $certificate_type)
                    <option value="{{ $certificate_type }}"{{ $certificate->type == $certificate_type ? ' selected' : '' }}>
                        {{ $certificate_type == 'Others' ? trans('label.other') : $certificate_type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="score-board{{ in_array($certificate->type, ['TESOL', 'Others']) ? ' hide' : ''  }}">
            <div class="row">
                <div class="col-xs-4{{ !isset($certificate->meta['listening']) ? ' hide' : '' }}">
                    <div class="form-group">
                        <label for="inputFreshCertificateScoreListening-{{ $certificate->id }}" class="control-label">{{ trans('label.score_listening') }}</label>
                        <input type="text" class="form-control" id="inputFreshCertificateScoreListening-{{ $certificate->id }}"
                               name="meta[listening]" value="{{ empty($certificate->meta['listening']) ? '0' : $certificate->meta['listening'] }}">
                    </div>
                </div>
                <div class="col-xs-4{{ !isset($certificate->meta['writing']) ? ' hide' : '' }}">
                    <div class="form-group">
                        <label for="inputFreshCertificateScoreWriting-{{ $certificate->id }}" class="control-label">{{ trans('label.score_writing') }}</label>
                        <input type="text" class="form-control" id="inputFreshCertificateScoreWriting-{{ $certificate->id }}"
                               name="meta[writing]" value="{{ empty($certificate->meta['writing']) ? '0' : $certificate->meta['writing'] }}">
                    </div>
                </div>
                <div class="col-xs-4{{ !isset($certificate->meta['reading']) ? ' hide' : '' }}">
                    <div class="form-group">
                        <label for="inputFreshCertificateScoreReading-{{ $certificate->id }}" class="control-label">{{ trans('label.score_reading') }}</label>
                        <input type="text" class="form-control" id="inputFreshCertificateScoreReading-{{ $certificate->id }}"
                               name="meta[reading]" value="{{ empty($certificate->meta['reading']) ? '0' : $certificate->meta['reading'] }}">
                    </div>
                </div>
                <div class="col-xs-4{{ !isset($certificate->meta['speaking']) ? ' hide' : '' }}">
                    <div class="form-group">
                        <label for="inputFreshCertificateScoreSpeaking-{{ $certificate->id }}" class="control-label">{{ trans('label.score_speaking') }}</label>
                        <input type="text" class="form-control" id="inputFreshCertificateScoreSpeaking-{{ $certificate->id }}"
                               name="meta[speaking]" value="{{ empty($certificate->meta['speaking']) ? '0' : $certificate->meta['speaking'] }}">
                    </div>
                </div>
                <div class="col-xs-4{{ !isset($certificate->meta['overall']) ? ' hide' : '' }}">
                    <div class="form-group">
                        <label for="inputFreshCertificateScoreOverall-{{ $certificate->id }}" class="control-label">{{ trans('label.score_overall') }}</label>
                        <input type="text" class="form-control" id="inputFreshCertificateScoreOverall-{{ $certificate->id }}"
                               name="meta[overall]" value="{{ empty($certificate->meta['overall']) ? '0' : $certificate->meta['overall'] }}">
                    </div>
                </div>
                <div class="col-xs-4{{ !isset($certificate->meta['cefr']) ? ' hide' : '' }}">
                    <div class="form-group">
                        <label for="inputFreshCertificateScoreCEFR-{{ $certificate->id }}" class="control-label">{{ trans('label.score_cefr') }}</label>
                        <input type="text" class="form-control" id="inputFreshCertificateScoreCEFR-{{ $certificate->id }}"
                               name="meta[cefr]" value="{{ empty($certificate->meta['cefr']) ? '0' : $certificate->meta['cefr'] }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputCertificateProvidedBy-{{ $certificate->id }}" class="control-label">{{ trans('label.provided_by') }}</label>
            <input type="text" class="form-control" id="inputCertificateProvidedBy-{{ $certificate->id }}" name="provided_by" required placeholder="{{ trans('label.provided_by') }}" value="{{ $certificate->provided_by }}">
        </div>
        <div class="form-group">
            <label for="inputCertificateProvidedAt-{{ $certificate->id }}" class="control-label">{{ trans('label.provided_at') }}</label>
            <input type="text" class="form-control date-picker" id="inputCertificateProvidedAt-{{ $certificate->id }}" name="provided_at" placeholder="{{ trans('label.provided_at') }}" value="{{ $certificate->provided_at }}">
        </div>

        <div class="form-group">
            <label for="inputFreshCertificateImage" class="control-label">
                {{ trans('label.picture') }}
                @if(!empty($certificate->image))
                    (<a class="open-window" href="{{ $certificate->image }}"
                       data-name="_blank" data-width="800" data-height="600">
                        <i class="fa fa-external-link"></i>
                    </a>)
                @endif
            </label>
            <input type="file" id="inputFreshCertificateImage" name="image">
            <div class="help-block">{{ trans('label.max_upload_file_size', ['size' => asKb($max_upload_file_size)]) }}</div>
        </div>
        <div class="form-group">
            <label for="inputCertificateDescription-{{ $certificate->id }}" class="control-label">{{ trans('label.description') }}</label>
            <textarea id="inputCertificateDescription-{{ $certificate->id }}" class="form-control" name="description" rows="2" placeholder="{{ trans('label.description') }}">{{ $certificate->description }}</textarea>
        </div>
        <div class="form-group">
            <button type="reset" class="btn btn-default">{{ trans('form.action_reset') }}</button>
            <a role="button" class="btn btn-danger delete" href="{{ addRdrUrl(homeUrl('profile/user-certificates/{id}', ['id'=> $certificate->id])) }}">
                {{ trans('form.action_delete') }}
            </a>
            <button type="submit" class="btn btn-success pull-right">{{ trans('form.action_save') }}</button>
        </div>
    </form>
    <hr>
@endforeach
<div id="fresh-certificates" class="{{ $user_certificates->count() > 0 && count($errors->certificate) <= 0 ? 'hide' : '' }}">
    <div class="fresh-certificate">
        <form method="post" action="{{ homeUrl('profile/user-certificates') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if (count($errors->certificate) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->certificate->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <label for="inputFreshCertificateType" class="control-label">{{ trans('label.certificate_type') }}</label>
                <select class="form-control inputFreshCertificateType" id="inputFreshCertificateType" name="type" required>
                    <option value=""></option>
                    @foreach($certificate_types as $certificate_type)
                        <option value="{{ $certificate_type }}">
                            {{ $certificate_type == 'Others' ? trans('label.other') : $certificate_type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="score-board hide">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="inputFreshCertificateScoreListening" class="control-label">{{ trans('label.score_listening') }}</label>
                            <input type="text" class="form-control" id="inputFreshCertificateScoreListening" name="meta[listening]">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="inputFreshCertificateScoreWriting" class="control-label">{{ trans('label.score_writing') }}</label>
                            <input type="text" class="form-control" id="inputFreshCertificateScoreWriting" name="meta[writing]">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="inputFreshCertificateScoreReading" class="control-label">{{ trans('label.score_reading') }}</label>
                            <input type="text" class="form-control" id="inputFreshCertificateScoreReading" name="meta[reading]">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="inputFreshCertificateScoreSpeaking" class="control-label">{{ trans('label.score_speaking') }}</label>
                            <input type="text" class="form-control" id="inputFreshCertificateScoreSpeaking" name="meta[speaking]">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="inputFreshCertificateScoreOverall" class="control-label">{{ trans('label.score_overall') }}</label>
                            <input type="text" class="form-control" id="inputFreshCertificateScoreOverall" name="meta[overall]">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label for="inputFreshCertificateScoreCEFR" class="control-label">{{ trans('label.score_cefr') }}</label>
                            <input type="text" class="form-control" id="inputFreshCertificateScoreCEFR" name="meta[cefr]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputFreshCertificateProvidedBy" class="control-label">{{ trans('label.provided_by') }}</label>
                <input type="text" class="form-control" id="inputFreshCertificateProvidedBy" name="provided_by" required placeholder="{{ trans('label.provided_by') }}">
            </div>
            <div class="form-group">
                <label for="inputFreshCertificateProvidedAt" class="control-label">{{ trans('label.provided_at') }}</label>
                <input type="text" class="form-control date-picker" id="inputFreshCertificateProvidedAt" name="provided_at" placeholder="{{ trans('label.provided_at') }}">
            </div>
            <div class="form-group">
                <label for="inputFreshCertificateImage" class="control-label">{{ trans('label.picture') }}</label>
                <input type="file" id="inputFreshCertificateImage" name="image">
                <div class="help-block">{{ trans('label.max_upload_file_size', ['size' => asKb($max_upload_file_size)]) }}</div>
            </div>
            <div class="form-group">
                <label for="inputFreshCertificateDescription" class="control-label">{{ trans('label.description') }}</label>
                <textarea id="inputFreshCertificateDescription" class="form-control" name="description" rows="2" placeholder="{{ trans('label.description') }}"></textarea>
            </div>
            <div class="form-group">
                <button type="reset" class="btn btn-default">{{ trans('form.action_reset') }}</button>
                <button type="submit" class="btn btn-success pull-right">{{ trans('form.action_add') }}</button>
            </div>
        </form>
    </div>
</div>
<button type="button" class="btn btn-primary btn-block certificate-more{{ $user_certificates->count() > 0 && count($errors->certificate) <= 0 ? '' : ' hide' }}">
    {{ trans('form.action_add_more') }}
</button>