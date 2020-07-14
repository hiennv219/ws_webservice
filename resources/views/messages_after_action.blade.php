@if (count($errors) > 0)
    <div class="alert alert-danger">
        @foreach ($errors->all() as $m)
            <p>{{ $m }}</p>
        @endforeach
    </div>
@elseif(count($successes) > 0)
    <div class="alert alert-success">
        @foreach ($successes->all() as $m)
            <p>{{ $m }}</p>
        @endforeach
    </div>
@elseif(count($info) > 0)
    <div class="alert alert-info">
        @foreach ($info->all() as $m)
            <p>{{ $m }}</p>
        @endforeach
    </div>
@endif