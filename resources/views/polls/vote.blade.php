@extends('layouts.app')

@section('page-css')
<link href="/css/styles.css" rel="stylesheet">

@if($graph != false)
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endif

@endsection


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-10 mb-3">
          <div class="card">
            <div class="card-header">Poll Detail</div>
            <div class="card-body">
              @if (session()->has('alert'))
              <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>{{ session()->get('alert') }}</strong>
              </div>
              @endif
              <p class="card-text h3">
                {{ $poll->title }} {{ $poll->status == 'Closed' ? '(Closed)' : '' }}
              </p>
              <p class="card-text">
                {{ $poll->description }}<br />
                By: {{ $poll->Owner->name }}
              </p>
            </div>
          </div>
      </div>

      @if($voted == false)
      <div class="col-md-10">
        <div class="card mb-3">
          <div class="card-body">
            <form action="{{ route('poll.vote') }}" method="post">
              @csrf
              <input type="hidden" name="pid" value="{{ $poll->id }}" />
              <div class="frb-group">

              </div>
              @foreach($poll->options as $key => $ap)
              <div class="frb frb-primary">
    						<input id="radio{{$key}}" name="voteid" type="radio" value="{{ $ap->id }}" {{ $key == 0 ? 'checked' : '' }}/>
    						<label for="radio{{$key}}">
    							<span class="frb-title">{{ $ap->label }}</span>
    							<span class="frb-description">{{ $ap->description }}</span>
    						</label>
    					</div>
              @endforeach

              @if($poll->anonymous == true || Auth::check())
              <button type="submit" class="btn btn-success m-3">Cast Vote</button>
              @else
              <a href="{{ route('poll.login', ['pid' => $poll->id])}}"><button type="button" class="btn btn-info m-3">Login to Vote</button></a>
              @endif
            </form>
          </div>
        </div>
      </div>
      @else
      <div class="col-md-10">
        <div class="card mb-3">
          <div class="card-body">
            {!! $graph->render() !!}
          </div>
        </div>
      </div>
      @endif

        <div class="col-md-10">
          <div class="card">
            <div class="card-body row justify-content-center">
              <a href="{{ route('poll.create')}}"><button class="btn btn-success m-1">Create Poll</button></a>
              <a href="{{ route('poll.my')}}"><button class="btn btn-primary m-1">My Polls</button></a>
              @if(Auth::check() && Auth::user()->admin == true && $poll->status == 'Active')
              <form method="POST" action="{{ route('poll.delete', [], false) }}">
                @csrf
                <input type="hidden" name="pid" value="{{ $poll->id }}"/>
                <button type="submit" class="btn btn-danger m-1">CLose Poll as admin</button>
              </form>
              @endif
            </div>
          </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')

<script type="text/javascript" defer>''
$(document).ready(function() {

} );
</script>
@stop
