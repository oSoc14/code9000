@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid" id="content-container">
  <div class="row">
      <div class="col-xs-6">
          <h1>{{ucfirst(trans('educal.groups'))}}</h1>
      </div>
      <div class="col-xs-6">
          <a type="button" class="btn btn-default btn-lg btn-educal-primary pull-right" href="{{route('group.create')}}" id="addEvent">
            <i class="fa fa-plus"></i> Add group
          </a>
      </div>
  </div>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th class="hidden-xs">#</th>
            <th>Name</th>
            <th>iCal</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach($groups as $group)
        <?php $i++ ?>
        <tr>
          <td class="hidden-xs">{{ $i }}</td>
          <td>{{ $group->name }}</td>
          @if($group->school)
          <td><a href='./export/{{$group->school->short}}/{{str_replace($group->school->short."_","",$group->name)}}'>/export/{{$group->school->short}}/{{str_replace($group->school->short."_","",$group->name)}}</a></td>
          @else
          <td>NO EXPORT</td>
          @endif
          <td>
            <a href="{{route('group.edit',$group->id)}}"><i class="fa fa-edit fa-2x"></i></a>&nbsp;
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
        $('#groupTable').dataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [2]}
            ]
        });
    } );
</script>
@stop