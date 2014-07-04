@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
  <h1>About</h1>
  <p>EduCal is an application for schools to easily create and manage calendars that can be shared with parents.</p>
  <h3>What is this?</h3>
  <p>Schools can have a lot of activities throughout the year such as commitee meetings, open days for the public, kid's parties, etc. This can be hard to keep track of for parents. EduCal centralizes and creates an easy-to-use link to import the school's agenda.</p>
  <h3>Credit</h3>
  <p>Authors: Bjorn Van Acker, Sander Meert, Nick Denys<br>
    Copyright 2014 OKFN Belgium</p>
      </div>
    </div>
  </div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop