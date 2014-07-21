@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid" id="content-container">
  <div class="row first-row">
    <div class="col-xs-12">
      <a href="{{ route('school.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.schools')]))}}</a>
      <h1>{{$school->name}}</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ucfirst(trans('educal.group'))}}</th>
            <th>{{ucfirst(trans('educal.#ofusers'))}}</th>
            <th>{{ucfirst(trans('educal.actions'))}}</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=0; ?>
          @foreach($school->groups as $group)
          <?php $i++ ?>
          <tr>
            <td>{{ $i }}</td>
            <td>{{ HTML::linkRoute('group.edit', $group->name, ['id' => $group->id], []) }}</td>
            <?php $group2 = Sentry::findGroupByName($group->name); ?>
            <td>{{ count(Sentry::findAllUsersInGroup($group2)) }}</td>
            <td>
              <a href="{{ route('group.edit', $group->id) }}" title="Edit"><i class="fa fa-pencil fa-2x"></i></a>
              <a data-toggle="modal" data-target="#confirm-delete" href="#" data-href="{{ route('group.delete', $group->id) }}" title="Remove"><i class="fa fa-times-circle fa-2x"></i></a>
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
                Are you sure you want to delete this group?
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

{{ HTML::script('//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.js') }}
{{ HTML::style('//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.css') }}

{{ HTML::script('js/app.js') }}

<script>
  $(document).ready(function() {
    $('#groupTable').dataTable();
  } );
</script>
@stop