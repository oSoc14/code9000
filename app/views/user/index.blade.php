@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="row">
  <div class="col-xs-12">
    <ol class="breadcrumb">
      <li><a href="{{ route('landing') }}">Home</a></li>
      <li class="active">Users</li>
    </ol>
  </div>
</div>
<h1>Users</h1>
<div class="row">
  <div class="col-xs-12 table-responsive">
    <table id="groupTable" class="table table-striped" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th class="hidden-xs">#</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Email</th>
        <th>Activated?</th>
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
          <span class="loader glyphicon glyphicon-cog"></span>
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
                {"bSortable": false, "aTargets": [4]}
            ]
        });
    } );
</script>
@stop