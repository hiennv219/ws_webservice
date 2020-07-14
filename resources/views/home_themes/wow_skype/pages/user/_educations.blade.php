@foreach($user_educations as $education)
    <form id="education-{{ $education->id }}" method="post" action="{{ homeUrl('profile/user-educations/{id}', ['id' => $education->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @if (count($errors->{'education_' . $education->id}) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->{'education_' . $education->id}->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label for="inputEducationSchool-{{ $education->id }}" class="control-label">{{ trans('label.school') }}</label>
            <input type="text" class="form-control" id="inputEducationSchool-{{ $education->id }}" name="school" required placeholder="{{ trans('label.school') }}" value="{{ $education->school }}">
        </div>
        <div class="form-group">
            <label for="inputEducationField-{{ $education->id }}" class="control-label">{{ trans('label.field') }}</label>
            <input type="text" class="form-control" id="inputEducationField-{{ $education->id }}" name="field" required placeholder="{{ trans('label.field') }}" value="{{ $education->field }}">
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputEducationStartMonth-{{ $education->id }}" class="control-label">{{ trans('label.month_start') }}</label>
                    <select id="inputEducationStartMonth-{{ $education->id }}" class="form-control" name="start_month">
                        <option value="0"></option>
                        @for($i = 1; $i <= 12; ++$i)
                            <option value="{{ $i }}"{{ $i == $education->start_month ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputEducationStartYear-{{ $education->id }}" class="control-label">{{ trans('label.year_start') }}</label>
                    <select id="inputEducationStartYear-{{ $education->id }}" class="form-control" name="start_year">
                        <option value="0"></option>
                        @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                            <option value="{{ $i }}"{{ $i == $education->start_year ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputEducationEndMonth-{{ $education->id }}" class="control-label">{{ trans('label.month_end') }}</label>
                    <select id="inputEducationEndMonth-{{ $education->id }}" class="form-control" name="end_month">
                        <option value="0"></option>
                        @for($i = 1; $i <= 12; ++$i)
                            <option value="{{ $i }}"{{ $i == $education->end_month ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputEducationEndYear-{{ $education->id }}" class="control-label">{{ trans('label.year_end') }}</label>
                    <select id="inputEducationEndYear-{{ $education->id }}" class="form-control" name="end_year">
                        <option value="0"></option>
                        @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                            <option value="{{ $i }}"{{ $i == $education->end_year ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEducationDescription-{{ $education->id }}" class="control-label">{{ trans('label.description') }}</label>
            <textarea id="inputEducationDescription-{{ $education->id }}" class="form-control" name="description" rows="2" placeholder="{{ trans('label.description') }}">{{ $education->description }}</textarea>
        </div>
        <div class="form-group">
            <button type="reset" class="btn btn-default">{{ trans('form.action_reset') }}</button>
            <a role="button" class="btn btn-danger delete" href="{{ addRdrUrl(homeUrl('profile/user-educations/{id}', ['id'=> $education->id])) }}">
                {{ trans('form.action_delete') }}
            </a>
            <button type="submit" class="btn btn-success pull-right">{{ trans('form.action_save') }}</button>
        </div>
    </form>
    <hr>
@endforeach
<div id="fresh-educations" class="{{ $user_educations->count() > 0 && count($errors->education) <= 0 ? 'hide' : '' }}">
    <div class="fresh-education">
        <form method="post" action="{{ homeUrl('profile/user-educations') }}">
            {{ csrf_field() }}
            @if (count($errors->education) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->education->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <label for="inputFreshEducationSchool" class="control-label">{{ trans('label.school') }}</label>
                <input type="text" class="form-control" id="inputFreshEducationSchool" name="school" required placeholder="{{ trans('label.school') }}">
            </div>
            <div class="form-group">
                <label for="inputFreshEducationField" class="control-label">{{ trans('label.field') }}</label>
                <input type="text" class="form-control" id="inputFreshEducationField" name="field" required placeholder="{{ trans('label.field') }}">
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshEducationStartMonth" class="control-label">{{ trans('label.month_start') }}</label>
                        <select id="inputFreshEducationStartMonth" class="form-control" name="start_month">
                            <option value="0"></option>
                            @for($i = 1; $i <= 12; ++$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshEducationStartYear" class="control-label">{{ trans('label.year_start') }}</label>
                        <select id="inputFreshEducationStartYear" class="form-control" name="start_year">
                            <option value="0"></option>
                            @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshEducationEndMonth" class="control-label">{{ trans('label.month_end') }}</label>
                        <select id="inputFreshEducationEndMonth" class="form-control" name="end_month">
                            <option value="0"></option>
                            @for($i = 1; $i <= 12; ++$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshEducationEndYear" class="control-label">{{ trans('label.year_end') }}</label>
                        <select id="inputFreshEducationEndYear" class="form-control" name="end_year">
                            <option value="0"></option>
                            @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputFreshEducationDescription" class="control-label">{{ trans('label.description') }}</label>
                <textarea id="inputFreshEducationDescription" class="form-control" name="description" rows="2" placeholder="{{ trans('label.description') }}"></textarea>
            </div>
            <div class="form-group">
                <button type="reset" class="btn btn-default">{{ trans('form.action_reset') }}</button>
                <button type="submit" class="btn btn-success pull-right">{{ trans('form.action_add') }}</button>
            </div>
        </form>
    </div>
</div>
<button type="button" class="btn btn-primary btn-block education-more{{ $user_educations->count() > 0 && count($errors->education) <= 0 ? '' : ' hide' }}">
    {{ trans('form.action_add_more') }}
</button>