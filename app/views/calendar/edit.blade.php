@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

        @section('content')
        <!-- main area -->
<div class="col-xs-12">
  <ol class="breadcrumb">
    <li><a href="../">Home</a></li>
    <li><a href="{{ route('calendar.index') }}">Calendar</a></li>
    <li><a href="{{ route('event.detail', $event->id) }}">Event</a></li>
    <li class="active">Edit</li>
  </ol>
</div>

<div class="col-xs-12">
  @foreach ($errors->all() as $message)
    {{$message}}
  @endforeach
</div>

<div class="col-xs-12 col-sm-9">
  {{Form::open(array('route' => array('event.update',$event->id)))}}
  <h1>Edit Event</h1>

  <div class="form-group">
      <label for="title">Title</label>
      <input type="tex" class="form-control" id="title" name="title" value="{{$event->title}}" placeholder="{{$event->title}}">
  </div>
  <div class="form-group">
      <label for="group">Group</label>
      {{Form::select('group', $groups, $event->group_id, array('class'=>'form-control'));}}
  </div>
  <div class="form-group">
      <label for="description">Description</label>
      <textarea name="description" id="description" class="form-control" rows="3">{{$event->description}}</textarea>
  </div>
  <div class="form-group">
      <div class='input-group date'>
          <input type='text' id='datetimepicker1' value="{{date_format(new DateTime($event->start_date), 'Y/m/d H:i')}}" name="start" class="form-control" />
          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
          </span>
      </div>
  </div>
  <div class="form-group">
      <div class='input-group date'>
          <input type='text' id='datetimepicker2' value="{{date_format(new DateTime($event->end_date), 'Y/m/d H:i')}}" name="end" class="form-control" />
          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
          </span>
      </div>
  </div>
  <button type="submit" class="btn btn-default btn-educal-primary">Save changes</button>
  {{ Form::close(), PHP_EOL }}
  {{ Session::get('errorMessage') }}
</div><!-- /.col-xs-12 main -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop