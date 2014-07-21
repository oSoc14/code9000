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
            <a href="#" class="btn btn-lg btn-default btn-educal-warning pull-right" data-toggle="modal" data-target="#registerUserModal"><i class="fa fa-plus"></i> {{ucfirst(trans('educal.adduser'))}}</a>
        </div>
    </div>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table id="groupTable" class="table content-table" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th class="hidden-xs">#</th>
          <th data-class="expand">{{ucfirst(trans('educal.name'))}}</th>
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
                    <td class="hidden-xs">{{ $i }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>

                        <!-- TODO: fix colors -->
                        <label for="activateUser">
                            @if($user->activated == 1)
                            <input type="checkbox" data-userid="{{$user->id}}" class="activateUser checkbox" checked>
                            @else
                            <input type="checkbox" data-userid="{{$user->id}}" class="activateUser checkbox">
                            @endif
                        </label>

                        </a>
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
                    <h4 class="modal-title">Add a user</h4>
                </div>
                <div class="modal-body">
                    @if($errors->count())
                    <div class="alert alert-danger" role="alert">
                        <strong>Errors</strong>
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
                        <label for="user-email">Name</label>
                        <input type="text" class="form-control" id="user-name" name="name" placeholder="What's your given name?">
                    </div>
                    <div class="form-group">
                        <label for="user-email">Surname</label>
                        <input type="text" class="form-control" id="user-surname" name="surname" placeholder="What's your surname?">
                    </div>
                    <div class="form-group">
                        <label for="user-email">Email address</label>
                        <input type="email" class="form-control" id="user-email" name="email" placeholder="What's your email address?">
                    </div>
                    <div class="form-group">
                        <label for="user-password">Password</label>
                        <input type="password" class="form-control" id="user-password" name="password" placeholder="Choose a password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="user-password-confirmation" name="password_confirmation" placeholder="Repeat that password here">
                    </div>
                    <button type="submit" class="btn btn-default btn-educal-danger">Register</button>
                    {{ Form::close(), PHP_EOL }}
                </div>
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
                    Are you sure you want to delete this user?
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
  {{ HTML::script('packages/datatables/js/dataTables.bootstrap.js') }}
  {{ HTML::script('packages/responsive-datatables/js/dataTables.responsive.js') }}

  {{ HTML::script('js/app.js') }}

    @if(Session::get('lang') == 'nl')
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
            "url": "http://cdn.datatables.net/plug-ins/be7019ee387/i18n/Dutch.json"
          },
          "aoColumnDefs": [
            {"bSortable": false, "aTargets": [5]}
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
    @elseif(Session::get('lang') == 'en')
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
            "url": "http://cdn.datatables.net/plug-ins/be7019ee387/i18n/English.json"
          },
          "aoColumnDefs": [
            {"bSortable": false, "aTargets": [5]}
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
    @elseif(Session::get('lang') == 'fr')
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
            "url": "http://cdn.datatables.net/plug-ins/be7019ee387/i18n/French.json"
          },
          "aoColumnDefs": [
            {"bSortable": false, "aTargets": [5]}
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
    @elseif(Session::get('lang') == 'de')
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
            "url": "http://cdn.datatables.net/plug-ins/be7019ee387/i18n/German.json"
          },
          "aoColumnDefs": [
            {"bSortable": false, "aTargets": [5]}
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
    @endif
@stop
