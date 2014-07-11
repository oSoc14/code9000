@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="row">
  <div class="col-xs-12">
    <ol class="breadcrumb">
      <li><a href="{{ route('landing') }}">Home</a></li>
      <li><a href="{{ route('group.index') }}">Groups</a></li>
      <li>Group</li>
      <li class="active">Edit</li>
    </ol>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <h1>Edit Group <small><span class="label label-primary">{{ str_replace($group->school->short.'_','',
$group->name) }}</span></small></h1>
      <!-- TODO: Check checkboxes, global function for str_replace -->
      <h2>Group info</h2>
      {{Form::open(array('route' => array('group.update', $group->id)))}}
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
      <div class="form-group">
          <label for="user">Group name</label>
          @if(str_replace($group->school->short.'_','',$group->name) == 'global')
          <label class="alert-warning">This name can not be changed</label>
          {{Form::text('name', str_replace($group->school->short.'_','',$group->name) , ['class'=>'form-control', 'disabled'=>'disabled'])}}
          @else
          {{Form::text('name', str_replace($group->school->short.'_','',$group->name) , ['class'=>'form-control'])}}
          @endif
      </div>
      <div class="checkbox">
          <label>
              @if(isset($group->permissions['group']))
              <input type="checkbox" name="permissions[group]" checked> Group
              @else
              <input type="checkbox" name="permissions[group]"> Group
              @endif
          </label>
          <label>
              @if(isset($group->permissions['user']))
              <input type="checkbox" name="permissions[user]" checked> User
              @else
              <input type="checkbox" name="permissions[user]"> User
              @endif
          </label>
          <label>
              @if(isset($group->permissions['event']))
              <input type="checkbox" name="permissions[event]" checked> Event
              @else
              <input type="checkbox" name="permissions[event]"> Event
              @endif
          </label>
      </div>
      <button type="submit" class="btn btn-primary">Update Group</button>
      {{ Form::close(), PHP_EOL }}

      {{Form::open(array('route' => array('user.addToGroup',$group->id)))}}
    <h2><small>Add user</small></h2>
    <div class="row">

      <div class="col-xs-7">
      @if(count($smartUsers) > 0)
      {{Form::select('user', $smartUsers, [], array('class'=>'form-control'));}}
      </div>
      <div class="col-xs-3">
      <button type="submit" class="btn btn-default btn-educal-danger">Add user</button>
      @else
      <p>Geen gebruikers die kunnen toegevoegd worden</p>
      @endif
      {{ Form::close(), PHP_EOL }}
      </div>
    </div>
    </div>
</div>
<br>
<div class="row">
  <div class="col-xs-12 table-responsive">
    <h2><small>Users in this group</small></h2>
    <table id="userTable" class="table table-striped" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th>E-mail</th>
        <th>Name</th>
        <th>Permissions</th>
      </tr>
      </thead>

      <tbody>
      @foreach($users as $user)
      <tr>
        <td>{{ $user->email }}</td>
        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
        <td>
          <a class="editUser" href="{{ route('user.edit',$user->id) }}">
            <span class="glyphicon glyphicon-pencil"></span>
          </a>
          <a href="{{ route('user.removeFromGroup',[$user->id, $group->id]) }}">
            <span class="glyphicon glyphicon-remove-sign"></span>
          </a>
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
        $('#userTable').dataTable();
    } );
</script>
@stop