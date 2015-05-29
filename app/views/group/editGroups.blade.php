@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
<div class="row">
  <div class="col-xs-12">
    <a href="{{ route('group.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.groups')]))}}</a>
    <h1>{{ucfirst(trans('educal.editgroup'))}}</h1>

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

      {{ Form::open(array('route' => array('group.update', $group->id), 'class'=>'form form-horizontal')) }}

      <div class="form-group">
          <label for="user" class="col-md-2 control-label">{{ucfirst(trans('educal.groupname'))}}</label>
          <div class="col-md-8">
          <?php
              $grp = str_replace('__' . $group->school->id, '', $group->name);
            ?>
          @if($grp == $group->school->name || $grp == 'Administratie')
          <label class="alert-warning">{{ucfirst(trans('educal.nameunchangeable'))}}</label>
          {{Form::text('name', $grp, ['class'=>'form-control', 'disabled'=>'disabled'])}}
          @else
          {{Form::text('name', $grp, ['class'=>'form-control'])}}
          @endif
        </div>
      </div>
      @if($grp != 'global' && $grp != 'admin')
      <div class="form-group">
          <label class="col-md-2 control-label">{{ucfirst(trans('educal.permissions'))}}</label>
          <div class="col-md-8">
              <div class="checkbox">
                  <label>
                      @if(isset($group->permissions['group']))
                      <input type="checkbox" name="permissions[group]" checked> {{ucfirst(trans('educal.managegroups'))}}
                      @else
                      <input type="checkbox" name="permissions[group]"> {{ucfirst(trans('educal.managegroups'))}}
                      @endif
                  </label>
              </div>
              <div class="checkbox">
                  <label>
                      @if(isset($group->permissions['user']))
                      <input type="checkbox" name="permissions[user]" checked> {{ucfirst(trans('educal.manageusers'))}}
                      @else
                      <input type="checkbox" name="permissions[user]"> {{ucfirst(trans('educal.manageusers'))}}
                      @endif
                  </label>
              </div>
              <div class="checkbox">
                  <label>
                      @if(isset($group->permissions['event']))
                      <input type="checkbox" name="permissions[event]" checked> {{ucfirst(trans('educal.manageevents'))}}
                      @else
                      <input type="checkbox" name="permissions[event]"> {{ucfirst(trans('educal.manageevents'))}}
                      @endif
                  </label>
              </div>
          </div>
      </div>
      <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-save"></i> {{ucfirst(trans('educal.savechanges'))}}</button>
          </div>
      </div>
      @else
      <div class="form-group">
        <label class="col-md-2 control-label">{{ucfirst(trans('educal.permissions'))}}</label>
        <div class="col-md-8">
            <label class="alert-warning">{{ucfirst(trans('educal.permissionsunchangeable'))}}</label>
        </div>
      </div>
      @endif
      {{ Form::close(), PHP_EOL }}
    </div>
  </div>

<div class="row">
  <div class="col-xs-12 col-md-10">
    <div class="panel-group" id="accordionGroup">

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title"><a data-toggle="collapse" data-parent="#accordionGroup" href="#addUsersCollapse"><strong>{{ucfirst(trans('educal.adduser'))}}</strong></a></h2>
      </div>
      <div class="panel-body collapse out" id="addUsersCollapse">
        {{Form::open(array('route' => array('user.addToGroup',$group->id)))}}
        <div class="col-xs-7">
          @if(count($smartUsers) > 0)
          {{Form::select('user', $smartUsers, [], array('class'=>'form-control'));}}
        </div>
        <div class="col-xs-3">
          <button type="submit" class="btn btn-default btn-educal-primary">{{ucfirst(trans('educal.adduser'))}}</button>
          @else
          <p>{{ucfirst(trans('educal.nousers'))}}</p>
          @endif
          {{ Form::close(), PHP_EOL }}
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title"><a data-toggle="collapse" data-parent="#accordionGroup" href="#currentUsersCollapse"><strong>{{ucfirst(trans('educal.usersingroup'))}}</strong></a></h2>
      </div>
      <div class="panel-body collapse in" id="currentUsersCollapse">
        <table id="userTable" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>{{ucfirst(trans('educal.name'))}}</th>
              <th>{{ucfirst(trans('educal.email'))}}</th>
              <th>{{ucfirst(trans('educal.actions'))}}</th>
            </tr>
          </thead>
          <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
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
{{ HTML::script('packages/datatables/js/dataTables.bootstrap.js') }}
{{ HTML::script('packages/responsive-datatables/js/dataTables.responsive.js') }}

{{ HTML::script('js/app.js') }}
<script>
    $(document).ready(function() {
        $('#userTable').dataTable();
    } );
</script>
@stop