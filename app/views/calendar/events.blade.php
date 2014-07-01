@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
  <h1>Events</h1>
  <ul class="modules-overview">
    <li class="module row">
      <div class="module-detail-container col-xs-12 col-sm-6">
        <span>Event 1</span>
        <span class="pull-right">01-01-2015</span>
      </div>
    </li>
    <li class="module row">
      <div class="module-detail-container col-xs-12 col-sm-6">
        <span>Event 2</span>
        <span class="pull-right">01-01-2015</span>
      </div>
    </li>
    <li class="module row">
      <div class="module-detail-container col-xs-12 col-sm-6">
        <span>Event 3</span>
        <span class="pull-right">01-01-2015</span>
      </div>
    </li>
  </ul>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop