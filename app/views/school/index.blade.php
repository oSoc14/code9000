@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
{{ HTML::style('packages/datatables/css/dataTables.bootstrap.css') }}
{{ HTML::style('packages/responsive-datatables/css/dataTables.responsive.css') }}
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
            <th data-class="expand">Name</th>
            <th data-hide="phone" data-name="City">City</th>
            <th data-hide="phone,tablet" data-name="Short name">Short name</th>
            <th data-hide="phone,tablet" data-name="Groups"># of groups</th>
            <th data-hide="phone" data-name="Actions">Actions</th>
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
                Confirmation
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-educal-warning" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-educal-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<div id="content-bg"></div>
@stop

@section('footerScript')

{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}
{{ HTML::script('packages/datatables/js/dataTables.bootstrap.js') }}
{{ HTML::script('packages/responsive-datatables/js/dataTables.responsive.js') }}

{{ HTML::script('js/app.js') }}

@stop

