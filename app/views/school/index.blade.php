@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="row">
  <div class="col-xs-12">
    <ol class="breadcrumb">
      <li><a href="{{ route('landing') }}">Home</a></li>
      <li class="active">Schools</li>
    </ol>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 table-responsive">
    <h1>Schools</h1>
    <table id="groupTable" class="table table-striped" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th>Name</th>
        <th>Short Name</th>
        <th># Groups</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      @foreach($schools as $school)
      <tr>
        <td>{{ HTML::linkRoute('school.detail', $school->name, ['id' => $school->id], []) }}</td>
        <td>{{$school->short}}</td>
        <td>{{count($school->groups)}}</td>
        <td>
          <a href="#" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="#" title="Remove"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@stop

@section('footerScript')


{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}

{{ HTML::script('//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.js') }}
{{ HTML::style('//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.css') }}

{{ HTML::script('js/app.js') }}

<script>
  $(document).ready(function() {
    $('#groupTable').dataTable();
  } );
</script>
@stop

