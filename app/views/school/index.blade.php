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
            <th data-class="expand">{{ucfirst(trans('educal.name'))}}</th>
            <th data-hide="phone" data-name="{{ucfirst(trans('educal.city'))}}">{{ucfirst(trans('educal.city'))}}</th>
            <th data-hide="phone,tablet" data-name="{{ucfirst(trans('educal.#ofgroups'))}}">{{ucfirst(trans('educal.#ofgroups'))}}</th>
            <th data-hide="phone" data-name="{{ucfirst(trans('educal.actions'))}}">{{ucfirst(trans('educal.actions'))}}</th>
          </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach($schools as $school)
        <?php $i++; ?>
        <tr>
          <td>{{ HTML::linkRoute('school.detail', $school->name, ['id' => $school->id], []) }}</td>
          <td>{{ $school->city }}</td>
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
                <button type="button" class="btn btn-default btn-educal-warning" data-dismiss="modal">{{ucfirst(trans('educal.cancel'))}}</button>
                <a href="#" class="btn btn-educal-danger">{{ucfirst(trans('educal.delete'))}}</a>
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

<?php
if(Session::get('lang') == 'nl') {
    $js = 'Dutch';
} elseif(Session::get('lang') == 'en') {
    $js = 'English';
} elseif(Session::get('lang') == 'fr') {
    $js = 'French';
}

// Paging hack, disable paging when there's less than 10 results
if(count($schools) > 10) {
    $pag = true;
} else {
    $pag = false;
}
?>
<script>
  $(document).ready(function() {
    var responsiveHelper;
    var breakpointDefinition = {
      tablet: 1024,
      phone : 480
    };
    var tableElement = $('#groupTable');
    tableElement.dataTable({
      "language": {
        "url": "packages/datatables/lang/{{$js}}.json"
      },
      "aoColumnDefs": [
        {"bSortable": false, "aTargets": [2, 3]}
      ],
      autoWidth        : false,
      {{ 'paging: '.($pag ? 'true' : 'false').','; }}
      preDrawCallback: function () {
        // Initialize the responsive datatables helper once.
        if (!responsiveHelper) {
          responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
        }
      },
      rowCallback    : function (nRow) {
        responsiveHelper.createExpandIcon(nRow);
      },
      drawCallback   : function (oSettings) {
        responsiveHelper.respond();
      }
    });
  } );
</script>
@stop

