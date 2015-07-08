@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
{{ HTML::style('packages/datatables/css/dataTables.bootstrap.css') }}
{{ HTML::style('bower_components/datatables-responsive/css/dataTables.responsive.css') }}
@stop

@section('content')
<div class="container-fluid" id="content-container">
  <div class="first-row row">
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
            <th data-class="expand">{{ucfirst(trans('educal.group'))}}</th>
            <th data-hide="phone,tablet" data-name="{{ucfirst(trans('educal.#ofusers'))}}">{{ucfirst(trans('educal.#ofusers'))}}</th>
            <th data-hide="phone" data-name="{{ucfirst(trans('educal.actions'))}}">{{ucfirst(trans('educal.actions'))}}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($school->groups as $group)
          <tr>
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
                <button type="button" class="btn btn-default btn-educal-danger" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-default btn-educal-warning">Delete</a>
            </div>
        </div>
    </div>
</div>
<div id="content-bg"></div>
@stop

@section('footerScript')

{{ HTML::script('bower_components/datatables/media/js/jquery.dataTables.min.js') }}
{{ HTML::script('packages/datatables/js/dataTables.bootstrap.js') }}
{{ HTML::script('bower_components/datatables-responsive/js/dataTables.responsive.js') }}
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
    if(count($school->groups) > 10) {
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
        "url": "http://cdn.datatables.net/plug-ins/be7019ee387/i18n/{{$js}}.json"
      },
      "aoColumnDefs": [
        {"bSortable": false, "aTargets": [2]}
      ],
      autoWidth        : false,
      {{ 'paging: '.($pag ? 'true' : 'false').','; }}
      {{ 'info: '.($pag ? 'true' : 'false').','; }}
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