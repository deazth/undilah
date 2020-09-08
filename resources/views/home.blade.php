@extends('layouts.app')

@section('content')
<div class="container-fluid">
  @if (session()->has('alert'))
  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>{{ session()->get('alert') }}</strong>
  </div>
  @endif
    <div class="row justify-content-center">
        <div class="col-md-12 mb-3">
          <div class="card">
            <div class="card-header">Recent Polls</div>
            <div class="card-body">
              <div class="row justify-content-center">
                @foreach($newp as $ap)
                <div class="col-md-4 mb-3">
                  <div class="card">
                    <a href="{{ route('poll.view', ['pid' => $ap->id])}}">
                    <div class="card-header">{{ $ap->title }}</div></a>
                    <div class="card-body">
                      {{ $ap->description }}<br />
                      By: <a href="{{ route('staff', ['staff_id' => $ap->user_id])}}">{{ $ap->Owner->name }}</a>
                    </div>
                    <div class="card-footer">{{ $ap->Users()->count() }} votes</div>
                  </div>

                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-10">
          <div class="card">
            <div class="card-body row justify-content-center">
              <a href="{{ route('poll.create')}}"><button class="btn btn-success m-1">Create Poll</button></a>
              <a href="{{ route('poll.my')}}"><button class="btn btn-primary m-1">My Polls</button></a>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
