@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>Schools</h1>

<ul class="modules-overview">
  <li class="module row">
    <div class="module-detail-container col-xs-12 col-sm-6">
      <span>Group 1</span>
      <span class="pull-right"># users</span>
    </div>
  </li>
  <li class="module row">
    <div class="module-detail-container col-xs-12 col-sm-6">
      <span>Group 2</span>
      <span class="pull-right"># users</span>
    </div>
  </li>
  <li class="module row">
    <div class="module-detail-container col-xs-12 col-sm-6">
      <span>Group 3</span>
      <span class="pull-right"># users</span>
    </div>
  </li>
</ul>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop