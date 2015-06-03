@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
{{ HTML::style('packages/datatables/css/dataTables.bootstrap.css') }}
{{ HTML::style('packages/responsive-datatables/css/dataTables.responsive.css') }}
@stop

@section('content')
<div class="container-fluid" id="content-container">
  <div class="row first-row">
      <div class="col-xs-6">
          <h1>{{ucfirst(trans('educal.groups'))}}</h1>
      </div>
      <div class="col-xs-6">
          <a type="button" class="btn btn-default btn-lg btn-educal-warning pull-right hidden-xs" href="{{route('group.create')}}" id="addEvent">
            <i class="fa fa-plus"></i> {{ucfirst(trans('educal.addgroup'))}}
          </a>
          <a type="button" class="btn btn-default btn-lg btn-educal-warning pull-right visible-xs" href="{{route('group.create')}}" id="addEvent">
            <i class="fa fa-plus"></i>
          </a>
      </div>
  </div>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th data-class="expand">{{ucfirst(trans('educal.name'))}}</th>
            <th data-hide="phone" data-name="URLs">{{ucfirst(trans('educal.urls'))}}</th>
            <th data-hide="phone" data-name="URL options">{{ucfirst(trans('educal.urloptions'))}}</th>
            <th data-hide="phone,tablet" data-name="Actions">{{ucfirst(trans('educal.actions'))}}</th>
          </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
        <tr>
          <td><a href="{{route('group.edit',$group->id)}}">{{ str_replace('__' . $group->school->id, '', $group->name) }}</a></td>
          @if($group->school)
          <td>
              <input type="text" class="form-control linkToText linkToText_{{$group->id}}" value="{{ URL::to('/') }}/export/{{$group->id}}/{{$group->school->name}}/{{str_replace('__' . $group->school->id, '', $group->name)}}" />
          </td>
          <td>
            <a href="#" data-group-id="{{$group->id}}" data-link="{{ URL::to('/') }}/export/{{$group->id}}/{{$group->school->name}}/{{str_replace('__' . $group->school->id, '', $group->name)}}" title="Switch to iCal link" class="linkTo"><i class="fa fa-calendar fa-2x"></i></a>
       <!--     <a href="#" data-group-id="{{$group->id}}" data-link="{{ URL::to('/') }}/export/pdf/{{$group->id}}/{{$group->school->name}}/{{str_replace('__' . $group->school->id, '', $group->name)}}" title="Switch to PDF link" class="linkTo"><i class="fa fa-file-pdf-o fa-2x"></i></a> -->
          </td>
          @else
          <td>{{ucfirst(trans('educal.noexport'))}}</td>
          <td></td>
          @endif
          <td>
         <!--   <a href="export/pdf/{{$group->id}}/{{$group->school->name}}/{{str_replace('__' . $group->school->id, '', $group->name)}}" title="Download Pdf">
              <span class="fa-stack">
                <i class="fa fa-file fa-stack-2x"></i>
                <i class="fa fa-download fa-inverse fa-stack-1x"></i>
              </span></a> -->
            <a href="{{route('group.edit',$group->id)}}"><i class="fa fa-pencil fa-2x"></i></a>
            <a href="#" data-toggle="modal" data-target="#confirm-delete" title="Remove" data-href="{{route('group.delete',$group->id)}}"><i class="fa fa-times-circle fa-2x"></i></a>
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
                <button type="button" class="btn btn-default btn-educal-warning" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-default btn-educal-danger">Delete</a>
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
    if(count($groups) > 10) {
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
        {"bSortable": false, "aTargets": [1, 2, 3]}
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