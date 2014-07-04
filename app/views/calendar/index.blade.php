@extends('layout.master')

@section('header')
{{ HTML::style("../packages/fullcalendar/fullcalendar.css") }}
{{ HTML::style("../css/calendar.css") }}
{{ HTML::style("../css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <a type="button" class="btn btn-default btn-lg" href="{{route('event.create')}}" id="addEvent">
          <span class="glyphicon glyphicon-pencil"></span> Add Events
      </a>

      <h1>Calendar</h1>

      <div id="calendar"></div>
      <div id="preloader">Loading...</div>
      <br>
    </div>
  </div>
</div>

@stop

@section('footerScript')
{{ HTML::script("packages/fullcalendar/lib/moment.min.js") }}
{{ HTML::script("packages/fullcalendar/fullcalendar.min.js") }}
{{ HTML::script('js/calendar.js') }}
{{ HTML::script('js/app.js') }}
@stop