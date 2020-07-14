@foreach($user_works as $work)
    <form id="work-{{ $work->id }}" method="post" action="{{ homeUrl('profile/user-works/{id}', ['id' => $work->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @if (count($errors->{'work_' . $work->id}) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->{'work_' . $work->id}->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label for="inputWorkCompany-{{ $work->id }}" class="control-label">{{ trans('label.company') }}</label>
            <input type="text" class="form-control" id="inputWorkCompany-{{ $work->id }}" name="company" required placeholder="{{ trans('label.company') }}" value="{{ $work->company }}">
        </div>
        <div class="form-group">
            <label for="inputWorkPosition-{{ $work->id }}" class="control-label">{{ trans('label.position') }}</label>
            <input type="text" class="form-control" id="inputWorkPosition-{{ $work->id }}" name="position" required placeholder="{{ trans('label.position') }}" value="{{ $work->position }}">
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputWorkStartMonth-{{ $work->id }}" class="control-label">{{ trans('label.month_start') }}</label>
                    <select id="inputWorkStartMonth-{{ $work->id }}" class="form-control" name="start_month">
                        <option value="0"></option>
                        @for($i = 1; $i <= 12; ++$i)
                            <option value="{{ $i }}"{{ $i == $work->start_month ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputWorkStartYear-{{ $work->id }}" class="control-label">{{ trans('label.year_start') }}</label>
                    <select id="inputWorkStartYear-{{ $work->id }}" class="form-control" name="start_year">
                        <option value="0"></option>
                        @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                            <option value="{{ $i }}"{{ $i == $work->start_year ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputWorkEndMonth-{{ $work->id }}" class="control-label">{{ trans('label.month_end') }}</label>
                    <select id="inputWorkEndMonth-{{ $work->id }}" class="form-control" name="end_month">
                        <option value="0"></option>
                        @for($i = 1; $i <= 12; ++$i)
                            <option value="{{ $i }}"{{ $i == $work->end_month ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="form-group">
                    <label for="inputWorkEndYear-{{ $work->id }}" class="control-label">{{ trans('label.year_end') }}</label>
                    <select id="inputWorkEndYear-{{ $work->id }}" class="form-control" name="end_year">
                        <option value="0"></option>
                        @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                            <option value="{{ $i }}"{{ $i == $work->end_year ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputWorkDescription-{{ $work->id }}" class="control-label">{{ trans('label.description') }}</label>
            <textarea id="inputWorkDescription-{{ $work->id }}" class="form-control" name="description" rows="2" placeholder="{{ trans('label.description') }}">{{ $work->description }}</textarea>
        </div>
        <div class="form-group">
            <button type="reset" class="btn btn-default">{{ trans('form.action_reset') }}</button>
            <a role="button" class="btn btn-danger delete" href="{{ addRdrUrl(homeUrl('profile/user-works/{id}', ['id'=> $work->id])) }}">
                {{ trans('form.action_delete') }}
            </a>
            <button type="submit" class="btn btn-success pull-right">{{ trans('form.action_save') }}</button>
        </div>
    </form>
    <hr>
@endforeach
<div id="fresh-works" class="{{ $user_works->count() > 0 && count($errors->work) <= 0 ? 'hide' : '' }}">
    <div class="fresh-work">
        <form method="post" action="{{ homeUrl('profile/user-works') }}">
            {{ csrf_field() }}
            @if (count($errors->work) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->work->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <label for="inputFreshWorkCompany" class="control-label">{{ trans('label.company') }}</label>
                <input type="text" class="form-control" id="inputFreshWorkCompany" name="company" required placeholder="{{ trans('label.company') }}">
            </div>
            <div class="form-group">
                <label for="inputFreshWorkPosition" class="control-label">{{ trans('label.position') }}</label>
                <input type="text" class="form-control" id="inputFreshWorkPosition" name="position" required placeholder="{{ trans('label.position') }}">
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshWorkStartMonth" class="control-label">{{ trans('label.month_start') }}</label>
                        <select id="inputFreshWorkStartMonth" class="form-control" name="start_month">
                            <option value="0"></option>
                            @for($i = 1; $i <= 12; ++$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshWorkStartYear" class="control-label">{{ trans('label.year_start') }}</label>
                        <select id="inputFreshWorkStartYear" class="form-control" name="start_year">
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
                        <label for="inputFreshWorkEndMonth" class="control-label">{{ trans('label.month_end') }}</label>
                        <select id="inputFreshWorkEndMonth" class="form-control" name="end_month">
                            <option value="0"></option>
                            @for($i = 1; $i <= 12; ++$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                        <label for="inputFreshWorkEndYear" class="control-label">{{ trans('label.year_end') }}</label>
                        <select id="inputFreshWorkEndYear" class="form-control" name="end_year">
                            <option value="0"></option>
                            @for($i = intval(date('Y')); $i >= 1980 ; --$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputFreshWorkDescription" class="control-label">{{ trans('label.description') }}</label>
                <textarea id="inputFreshWorkDescription" class="form-control" name="description" rows="2" placeholder="{{ trans('label.description') }}"></textarea>
            </div>
            <div class="form-group">
                <button type="reset" class="btn btn-default">{{ trans('form.action_reset') }}</button>
                <button type="submit" class="btn btn-success pull-right">{{ trans('form.action_add') }}</button>
            </div>
        </form>
    </div>
</div>
<button type="button" class="btn btn-primary btn-block work-more{{ $user_works->count() > 0 && count($errors->work) <= 0 ? '' : ' hide' }}">
    {{ trans('form.action_add_more') }}
</button>