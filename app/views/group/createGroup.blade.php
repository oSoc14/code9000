@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-md-10">
      <h1>Add Group</h1>
      {{ Form::open(array('route' => array('group.store'),'class'=>'form form-horizontal')) }}

      <!-- ERROR MESSAGES -->
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

      @if($schools)
      <div class="form-group">
        {{Form::label('name', 'Name', array('class'=>'col-md-2 control-label')) }}
        <div class="col-md-8">
        {{Form::text('name', null , ['class'=>'form-control', 'placeholder'=>'For example: "administrators"'])}}
        </div>
      </div>

      <div class="form-group">
        <label for="school" class="col-md-2 control-label">Which school is this group for?</label>
        <div class="col-md-8">
          {{Form::select('school', $schools, [], array('id'=>'school', 'class'=>'form-control'));}}
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2 control-label">Permissions</label>
        <div class="col-md-8">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="permissions[group]"> Can create groups
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="permissions[user]"> Can add users
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="permissions[event]" checked> Can add,edit and remove events
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-offset-2 col-md-8">
          <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-plus"></i> Create and add group</button>
        </div>
      </div>

      {{ Form::close(), PHP_EOL }}
      @else
      <p>There are no schools to add a group to.</p>
      @endif

    </div>
  </div>
</div>


@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}
{{ HTML::style('packages/datatables/css/jquery.dataTables.min.css') }}
<script>
    $(document).ready(function() {
        $('#userTable').dataTable();
    } );
</script>
@stop