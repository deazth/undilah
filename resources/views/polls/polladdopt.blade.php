@extends('layouts.app')

@section('page-css')

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
                {{ $poll->title }} {{ $poll->public == true ? '' : '(private)'}}
              </p>
              <p class="card-text">
                {{ $poll->description }} <br / />
                <!-- Close date: {{ $poll->end_time }} <br / /> -->
                Status: {{ $poll->status }}
                @if($poll->status == 'Active')
                <br  />Poll URL: {{ route('poll.view', ['pid' => $poll->id])}}
                @endif
              </p>

              <div class="row">
                @if($poll->status != 'Deleted')
                <div class="col">
                  <form method="POST" action="{{ route('poll.delete', [], false) }}">
                    @csrf
                    <input type="hidden" name="pid" value="{{ $poll->id }}"/>
                    <div class="form-group row mb-0 justify-content-center">
                        <button type="submit" class="btn btn-danger m-1">Close Poll</button>
                    </div>
                  </form>
                </div>
                @endif
                @if($poll->options->count() > 1 && $poll->status == 'Draft')
                <div class="col">
                  <form method="POST" action="{{ route('poll.publish', [], false) }}">
                    @csrf
                    <input type="hidden" name="pid" value="{{ $poll->id }}"/>
                    <div class="form-group row mb-0 justify-content-center">
                        <button type="submit" class="btn btn-success m-1">Publish Poll</button>
                    </div>
                  </form>
                </div>
                @endif
              </div>
            </div>
          </div>
      </div>
      @if($poll->status == 'Draft')
      <div class="col-md-10 mb-3">
          <div class="card">
            <div class="card-header">Add Poll Option</div>
            <div class="card-body">
              <form method="POST" action="{{ route('poll.addopt', [], false) }}">
                @csrf
                <input type="hidden" name="pid" value="{{ $poll->id }}"/>
                <div class="form-group row">
                    <label for="optlable" class="col-md-3 col-form-label text-md-right">Option Label</label>
                    <div class="col-md-6">
                        <input id="optlable" class="form-control" type="text" name="optlable" value="{{old('optlable')}}" required autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="remark" class="col-md-3 col-form-label text-md-right">Option tooltips</label>
                    <div class="col-md-8">
                      <textarea rows="3" class="form-control" id="remark" name="details" placeholder="Additional information about this poll option" required>{{ old('details') }}</textarea>
                    </div>
                </div>
                <div class="form-group row mb-0 justify-content-center">
                    <button type="submit" class="btn btn-primary m-1">Add Options</button>
                </div>
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
          <div class="card-header">List of Poll Options</div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="taskdetailtable" class="table table-striped table-bordered table-hover" >
                <thead>
                  <tr>
                    <th scope="col">Label</th>
                    <th scope="col">Desc</th>
                    @if($poll->status == 'Draft')
                    <th scope="col">Action</th>
                    @else
                    <th scope="col">Vote count</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($poll->options as $acts)
                  <tr>
                    <td>{{ $acts->label }}</td>
                    <td>{{ $acts->description }}</td>
                    <td class="text-center">
                      @if($poll->status == 'Draft')
                      <form action="{{ route('poll.remopt', [], false)}}"
                        method="post">
                        <input type="hidden" name="poid" value="{{ $acts->id }}" />
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash"></i></button>
                      </form>
                      @else
                      {{ $acts->votecounts() }}
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
  $('#taskdetailtable').DataTable();
} );
</script>
@stop
