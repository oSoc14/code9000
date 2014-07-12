@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid" id="content-container">
  <div class="first-row row">
    <div class="col-xs-12">
      <h1>{{ucfirst(trans('educal.schools'))}}</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th class="hidden-xs">#</th>
          <th>Name</th>
          <th>City</th>
          <th class="hidden-xs hidden-sm">Short name</th>
          <th class="hidden-xs"># of groups</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach($schools as $school)
        <?php $i++; ?>
        <tr>
          <td class="hidden-xs">{{ $i }}</td>
          <td>{{ HTML::linkRoute('school.detail', $school->name, ['id' => $school->id], []) }}</td>
          <td>{{ $school->city }}</td>
          <td class="hidden-xs hidden-sm">{{$school->short}}</td>
          <td class="hidden-xs">{{count($school->groups)}}</td>
          <td>
            <a href="{{ route('school.edit', $school->id) }}" title="Edit"><i class="fa fa-pencil fa-2x"></i></a>
            <a data-toggle="modal" data-target="#confirm-delete" href="#" data-href="{{ route('school.delete', $school->id) }}" title="Remove"><i class="fa fa-times-circle fa-2x"></i></a>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirmation
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<div id="content-bg"></div>
@stop

@section('footerScript')

{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}
{{ HTML::script('packages/datatables/js/dataTables.bootstrap.js') }}
{{ HTML::style('packages/datatables/css/dataTables.bootstrap.css') }}

{{ HTML::script('js/app.js') }}

<script>
  $(document).ready(function() {
    $('#groupTable').dataTable();
  } );
</script>
@stop

