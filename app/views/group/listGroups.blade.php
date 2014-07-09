@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="row">
  <div class="col-xs-12">
    <ol class="breadcrumb">
      <li><a href="{{ route('landing') }}">Home</a></li>
      <li class="active">Groups</li>
    </ol>
  </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-lg-6">
        <h1>Groups</h1>
    </div>
    <div class="col-xs-6 col-sm-6 col-lg-6">
        <a type="button" class="btn btn-default btn-lg btn-educal-primary pull-right" href="{{route('group.create')}}" id="addEvent">
            <span class="glyphicon glyphicon-plus"></span> Add group
        </a>
    </div>
</div>
<div class="row">
  <div class="col-xs-12 table-responsive">
    <table id="groupTable" class="table table-striped" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th>Name</th>
        <th>iCal</th>
        <th>Actions</th>
      </tr>
      </thead>

      <tbody>
      @foreach($groups as $group)
      <tr>
        <td>{{ $group->name }}</td>
        @if($group->school)
        <td><a href='./export/{{$group->school->short}}/{{str_replace($group->school->short."_","",$group->name)}}/ical.ics'>/export/{{$group->school->short}}/{{str_replace($group->school->short."_","",$group->name)}}/ical.ics</a></td>
        @else
        <td>NO EXPORT</td>
        @endif
        <td>
          <a href="{{route('group.edit',$group->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
          <a href="#"><span class="glyphicon glyphicon-remove-sign"></span></a>
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
        $('#groupTable').dataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [2]}
            ]
        });
    } );
</script>
@stop