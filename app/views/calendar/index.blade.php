@extends('layout.master')

@section('header')
<!-- jQuery UI -->
{{ HTML::style("css/jquery-ui.min.css") }}
{{ HTML::style("css/jquery-ui.structure.min.css") }}
{{ HTML::style("css/jquery-ui.theme.min.css") }}

{{ HTML::style("/packages/fullcalendar/fullcalendar.css") }}
{{ HTML::style("/css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-md-10">
      <ol class="breadcrumb">
        <li><a href="{{ route('landing') }}">Home</a></li>
        <li class="active">Calendar</li>
      </ol>
    </div>
    <div class="col-xs-12 col-sm-5">
      <h1>Calendar</h1>
    </div>
    <div class="col-xs-12 col-sm-5">
      <a type="button" class="btn btn-default btn-lg btn-educal-primary pull-right" href="{{route('event.create')}}" id="addEvent">
        <span class="glyphicon glyphicon-plus"></span> Add event
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 col-md-10">
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