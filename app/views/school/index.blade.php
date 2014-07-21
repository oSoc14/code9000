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
          <th>{{ucfirst(trans('educal.city'))}}</th>
          <th>{{ucfirst(trans('educal.name'))}}</th>
          <th>{{ucfirst(trans('educal.short'))}}</th>
          <th>{{ucfirst(trans('educal.#ofgroups'))}}</th>
          <th>{{ucfirst(trans('educal.actions'))}}</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach($schools as $school)
        <?php $i++; ?>
        <tr>
          <td class="hidden-xs">{{ $i }}</td>
          <td >{{ $school->city }}</td>
          <td>{{ HTML::linkRoute('school.detail', $school->name, ['id' => $school->id], []) }}</td>
          <td>{{$school->short}}</td>
          <td>{{count($school->groups)}}</td>
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
                {{ucfirst(trans('educal.confirmation'))}}
            </div>
            <div class="modal-body">
                {{ucfirst(trans('educal.confirmationmsg'))}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ucfirst(trans('educal.cancel'))}}</button>
                <a href="#" class="btn btn-danger danger">{{ucfirst(trans('educal.delete'))}}</a>
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
    $('#groupTable').dataTable( {
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/be7019ee387/i18n/Dutch.json"
        }
    } );
  } );
</script>
@stop

