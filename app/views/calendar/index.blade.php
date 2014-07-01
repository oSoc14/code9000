@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>Calendar</h1>
<div id="calendar"></div>
@stop

@section('footerScript')
{{ HTML::style("packages/fullcalendar/fullcalendar.css") }}
{{ HTML::style("css/calendar.css") }}
{{ HTML::script("packages/fullcalendar/lib/moment.min.js") }}
{{ HTML::script("packages/fullcalendar/fullcalendar.min.js") }}
{{ HTML::script('js/calendar.js') }}
{{ HTML::script('js/app.js') }}
@stop