@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<a type="button" class="btn btn-default btn-lg" href="{{route('event.create')}}" id="addEvent">
    <span class="glyphicon glyphicon-pencil"></span> Add Events
</a>

<h1>Calendar</h1>

<div id="calendar"></div>
<div id="preloader">Loading...</div>
<br>

@stop

@section('footerScript')
{{ HTML::style("packages/fullcalendar/fullcalendar.css") }}
{{ HTML::style("css/calendar.css") }}
{{ HTML::script("packages/fullcalendar/lib/moment.min.js") }}
{{ HTML::script("packages/fullcalendar/fullcalendar.min.js") }}
{{ HTML::script('js/calendar.js') }}
{{ HTML::script('js/app.js') }}
@stop