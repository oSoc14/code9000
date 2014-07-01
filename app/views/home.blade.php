@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')

<div class="container-fluid">
  <header class="top-bar">
    <h1>EduCal</h1>
  </header>
  <div class="sidebar-container">
    <nav>
      <ul>
        <li>Schools</li>
        <li>User Management</li>
        <li>Groups</li>
        <li>Events</li>
        <li>Settings</li>
        <li>About</li>
      </ul>
    </nav>
  </div>
  <div class="content-container">

  </div>
</div>

@stop