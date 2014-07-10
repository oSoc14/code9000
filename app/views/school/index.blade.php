@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid" id="content-container">
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <h1>{{ucfirst(trans('educal.schools'))}}</h1>
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th class="hidden-xs">#</th>
          <th>Name</th>
          <th>Short Name</th>
          <th># of Groups</th>
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
          <td>{{$school->short}}</td>
          <td>{{count($school->groups)}}</td>
          <td>
            <a href="#" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
            <a href="#" title="Remove"><i class="fa fa-times-circle fa-2x"></i></a>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<div id="content-bg"></div>
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

