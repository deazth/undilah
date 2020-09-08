@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">My Polls</div>
        <div class="card-body">
          <div class="table-responsive">
            <a href="{{ route('poll.create')}}"><button class="btn btn-success mb-3">Create Poll</button></a>
            <table id="taskdetailtable" class="table table-striped table-bordered table-hover" >
              <thead>
                <tr>
                  <th scope="col">Close Date</th>
                  <th scope="col">Title</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($polls as $acts)
                <tr>
                  <td>{{ $acts->end_time }}</td>
                  <td>{{ $acts->title }}</td>
                  <td>{{ $acts->status }} @if($acts->status != 'Draft') - {{ $acts->Users()->count() }} votes @endif </td>
                  <td class="text-center">
                    <a href="{{ route('poll.view', ['pid' => $acts->id ]) }}">
                      <button type="button" class="btn btn-sm btn-info" title="Info" ><i class="fa fa-info"></i></button>
                    </a>
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
