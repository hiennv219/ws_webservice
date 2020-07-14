@if($choices->count() > 0)
    <div id="{{ $html_id }}" class="widget-polls">
        <div class="panel panel-default">
            @if(!empty($name))
                <div class="panel-heading">
                    <h4 class="panel-title">{{ $name }}</h4>
                </div>
            @endif
            <div class="panel-body">
                @if(!empty($poll_name))
                    <h5><strong>{{ $poll_name }}</strong></h5>
                @endif
                @if(!empty($poll_description))
                    <div class="help-block small">{{ $poll_description }}</div>
                @endif
                <form class="poll-votes hide" method="post" data-id="{{ $poll_id }}"
                      action="{{ addRdrUrl(addExtraUrl('web-api/polls/id', webApiUrl('extra')) . '&id=' . $poll_id) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="votes" value="1">
                    <input type="hidden" name="poll_id" value="{{ $poll_id }}">
                    <div class="form-group">
                        @foreach($choices as $choice)
                            <div class="{{ $input_type }}">
                                <label>
                                    <input type="{{ $input_type }}" name="choice_ids[]" id="inputChoice_{{ $choice->id }}" value="{{ $choice->id }}">
                                    {{ $choice->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ trans('polls.action_vote') }}</button>
                        <button type="button" class="btn btn-success show-poll-result">{{ trans('polls.action_view_result') }}</button>
                    </div>
                </form>
                <div class="poll-result hide">
                    <ul class="list-unstyled">
                        @foreach($choices as $choice)
                            <li>{{ $choice->name }}: {{ $choice->votes }} {{ trans_choice('polls.vote_lc', $choice->votes) }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn btn-success show-poll-votes">{{ trans('polls.action_view_votes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif