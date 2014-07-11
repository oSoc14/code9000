@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
<div class="row">
  <div class="col-xs-12">
    <a href="{{ route('group.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> Back to groups</a>
    <h1>Edit Group</h1>
      <!-- TODO: Check checkboxes, global function for str_replace -->

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

      {{ Form::open(array('route' => array('group.update', $group->id), 'class'=>'form form-horizontal')) }}

      <div class="form-group">
          <label for="user" class="col-md-2 control-label">Group name</label>
          <div class="col-md-8">
          <?php
           $grp = str_replace($group->school->short.'_','',$group->name);
            ?>
          @if($grp == 'global' || $grp == 'admin')
          <label class="alert-warning">This name can not be changed</label>
          {{Form::text('name', $grp, ['class'=>'form-control', 'disabled'=>'disabled'])}}
          @else
          {{Form::text('name', $grp, ['class'=>'form-control'])}}
          @endif
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2 control-label">User permissions</label>
        <div class="col-md-8">
          <div class="checkbox">
            <label>
                @if(isset($group->permissions['group']))
                <input type="checkbox" name="permissions[group]" checked> Can create groups
                @else
                <input type="checkbox" name="permissions[group]"> Can create groups
                @endif
            </label>
          </div>
          <div class="checkbox">
            <label>
                @if(isset($group->permissions['user']))
                <input type="checkbox" name="permissions[user]" checked> Can add users
                @else
                <input type="checkbox" name="permissions[user]"> Can add users
                @endif
            </label>
          </div>
          <div class="checkbox">
            <label>
                @if(isset($group->permissions['event']))
                <input type="checkbox" name="permissions[event]" checked> Can add, edit and remove events
                @else
                <input type="checkbox" name="permissions[event]"> Can add, edit and remove events
                @endif
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-save"></i> Save changes</button>
        </div>
      </div>
      {{ Form::close(), PHP_EOL }}
    </div>
  </div>

<div class="row">
  <div class="col-xs-12 col-md-10">
    <div class="panel-group" id="accordionGroup">

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title"><a data-toggle="collapse" data-parent="#accordionGroup" href="#addUsersCollapse"><strong>Add user</strong></a></h2>
      </div>
      <div class="panel-body collapse out" id="addUsersCollapse">
        {{Form::open(array('route' => array('user.addToGroup',$group->id)))}}
        <div class="col-xs-7">
          @if(count($smartUsers) > 0)
          {{Form::select('user', $smartUsers, [], array('class'=>'form-control'));}}
        </div>
        <div class="col-xs-3">
          <button type="submit" class="btn btn-default btn-educal-primary">Add user</button>
          @else
          <p>Geen gebruikers die kunnen toegevoegd worden</p>
          @endif
          {{ Form::close(), PHP_EOL }}
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title"><a data-toggle="collapse" data-parent="#accordionGroup" href="#currentUsersCollapse"><strong>Users in this group</strong></a></h2>
      </div>
      <div class="panel-body collapse in" id="currentUsersCollapse">
        <table id="userTable" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Name</th>
              <th>E-mail</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->email }}</td>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            <td>
              <a class="editUser" href="{{ route('user.edit',$user->id) }}" title="Edit user details">
                <i class="fa fa-pencil fa-2x"></i>
              </a>
              <a href="{{ route('user.removeFromGroup',[$user->id, $group->id]) }}" title="Remove user">
                <i class="fa fa-times-circle fa-2x"></i>
              </a>
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
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
        $('#userTable').dataTable();
    } );
</script>
@stop