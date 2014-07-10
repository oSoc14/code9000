@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="col-xs-12">
  <ol class="breadcrumb">
    <li><a href="../../">Home</a></li>
    <li><a href="{{ route('calendar.index') }}">Calendar</a></li>
    <li class="active">Event</li>
  </ol>
</div>

<div class="col-xs-12">
  <h1>{{$event->title}}</h1>
  <strong>Group:</strong>
  <p>{{$event->group->name}}</p>
</div>

<div class="col-xs-12">
  <strong>Description</strong>
  <p>{{$event->description}}</p>
</div>

<div class="col-xs-12">
  <strong>Starts:</strong>
      <p>{{date_format(new DateTime($event->start_date), 'Y/m/d - H:i')}}</p>
</div>

<div class="col-xs-12">
  <strong>Ends:</strong>
    <p>{{date_format(new DateTime($event->end_date), 'Y/m/d - H:i')}}</p>
</div>

<div class="col-xs-12">
  <!-- TODO: check if user has rights for edit, then display button -->
  <a type="button" class="btn btn-default btn-educal-danger" href="{{route('event.edit',$event->id)}}" id="editEvent">
      <span class="glyphicon glyphicon-pencil"></span> Edit Event
  </a>
</div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop