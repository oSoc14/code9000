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
            <h1>{{ucfirst(trans('educal.users'))}}</h1>
        </div>
        <div class="col-xs-6">
            <a href="#" class="btn btn-lg btn-default btn-educal-warning pull-right hidden-xs" data-toggle="modal" data-target="#registerUserModal"><i class="fa fa-plus"></i> {{ucfirst(trans('educal.adduser'))}}</a>
            <a href="#" class="btn btn-lg btn-default btn-educal-warning pull-right visible-xs" data-toggle="modal" data-target="#registerUserModal"><i class="fa fa-plus"></i></a>
        </div>
    </div>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th data-class="expand">{{ucfirst(trans('educal.firstname'))}}</th>
          <th>{{ucfirst(trans('educal.surname'))}}</th>
          <th data-hide="phone,tablet" data-name="{{ucfirst(trans('educal.email'))}}">{{ucfirst(trans('educal.email'))}}</th>
          <th data-hide="phone" data-name="{{ucfirst(trans('educal.activated'))}}">{{ucfirst(trans('educal.activated'))}}</th>
          <th data-hide="phone,tablet" data-name="{{ucfirst(trans('educal.actions'))}}">{{ucfirst(trans('educal.actions'))}}</th>
        </tr>
        </thead>
                <tbody>
                <?php $i = 0; ?>
                @foreach($users as $user)
                <?php $i++; ?>
                <tr>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <label for="activateUser">
                            @if($user->activated == 1)
                            <input type="checkbox" data-userid="{{$user->id}}" class="activateUser checkbox" checked>
                            @else
                            <input type="checkbox" data-userid="{{$user->id}}" class="activateUser checkbox">
                            @endif
                        </label>
                    </td>
                    <td>
                        <a href="{{ route('user.edit', $user->id) }}" title="Edit"><i class="fa fa-pencil fa-2x"></i></a>
                        <a data-toggle="modal" data-target="#confirm-delete" href="#" data-href="{{ route('user.removeUserFromSchool', $user->id) }}" title="Remove"><i class="fa fa-times-circle fa-2x"></i></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@if($errors->has('usererror'))
<div class="modal fade" id="registerUserModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="registerUserModal" aria-hidden="true">
    @else
    <div class="modal fade" id="registerUserModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="registerUserModal" aria-hidden="true">
        @endif
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{ucfirst(trans('educal.adduser'))}}</h4>
                </div>
                <div class="modal-body">
                    @if($errors->count())
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ucfirst(trans('educal.errors'))}}</strong>
                        <ul>
                            @foreach ($errors->all() as $message)
                            <li>{{$message}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{ Form::open([
                    'route' => 'user.create',
                    'data-ajax' => 'true',
                    ]), PHP_EOL }}
                    @if(Sentry::getUser()->hasAccess('school'))
                    <div class="form-group">
                        <label>School</label>
                        {{ Form::select('school', $schools, null, array('class' => 'form-control')) }}
                    </div>
                    @else
                    <input type="hidden" class="form-control" id="school" name="school" value="{{$school->id}}">
                    @endif
                    <div class="form-group">
                        {{Form::label('name', ucfirst(trans('educal.firstname')))}}
                        {{Form::text('name', null , ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('surname', ucfirst(trans('educal.surname')))}}
                        {{Form::text('surname', null , ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('email', ucfirst(trans('educal.email')))}}
                        {{Form::email('email', null , ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        <label for="user-password">{{ucfirst(trans('educal.password'))}}</label>
                        <input type="password" class="form-control" id="user-password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="user-password-confirmation">{{ucfirst(trans('educal.repeatpassword'))}}</label>
                        <input type="password" class="form-control" id="user-password-confirmation" name="password_confirmation">
                    </div>
                    @if(Sentry::getUser()->hasAccess('school'))
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="superAdmin" id="superAdmin">{{ucfirst(trans('educal.addAdmin'))}}
                        </label>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-default btn-educal-danger">{{ucfirst(trans('educal.register'))}}</button>
                    {{ Form::close(), PHP_EOL }}
                </div>
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
                    <button type="button" class="btn btn-educal-warning" data-dismiss="modal">{{ucfirst(trans('educal.cancel'))}}</button>
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
            {"bSortable": false, "aTargets": [3, 4]}
          ],
          autoWidth        : false,
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
