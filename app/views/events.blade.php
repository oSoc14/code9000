@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

<div class="page-container">

  @extends('layout.navbar')

  <div class="container">
    @extends('layout.sidebar')

    @section('content')
    <!-- main area -->
    <div class="col-xs-12 col-sm-9">
      <h1>Events</h1>
      <ul class="events-overview">
        <li class="event row">
          <div class="event-detail-container col-xs-12 col-sm-6">
            <span>Event 1</span>
            <span class="pull-right">01-01-2015</span>
          </div>
        </li>
        <li class="event row">
          <div class="event-detail-container col-xs-12 col-sm-6">
            <span>Event 2</span>
            <span class="pull-right">01-01-2015</span>
          </div>
        </li>
        <li class="event row">
          <div class="event-detail-container col-xs-12 col-sm-6">
            <span>Event 3</span>
            <span class="pull-right">01-01-2015</span>
          </div>
        </li>
      </ul>

    </div><!-- /.col-xs-12 main -->
  </div><!--/.row-->
</div><!--/.container-->
</div><!--/.page-container-->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop