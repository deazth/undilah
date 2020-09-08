@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header">Create Poll</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('poll.docreate', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="title" class="col-md-3 col-form-label text-md-right">Title</label>
                        <div class="col-md-6" >
                            <input id="title" class="form-control" type="text" name="title" value="{{old('title')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-3 col-form-label text-md-right">Extra Info</label>
                        <div class="col-md-8">
                          <textarea rows="3" class="form-control" id="remark" name="desc" placeholder="Additional information about this poll" required>{{ old('desc') }}</textarea>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="actdate" class="col-md-3 col-form-label text-md-right">End Date</label>
                        <div class="col-md-5" >
                            <input type="date" class="form-control" name="actdate" id="actdate" value="{{ old('actdate', $tomorrow) }}" min="{{ $tomorrow }}"/>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                          <input id="private" class="form-check-input" type="checkbox" name="privatepoll" >
                          <label for="private" class="form-check-label" title="Only those with the link can see this poll">Private poll</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                          <input id="anonpoll" class="form-check-input" type="checkbox" name="anonpoll" >
                          <label for="anonpoll" class="form-check-label" title="No need to login to cast vote">Anonymous poll</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0 justify-content-center">
                        <button type="submit" class="btn btn-primary m-1">Add Options</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
