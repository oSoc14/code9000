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
<div class="container-fluid" id="content-container">
    <div class="row">
        <div class="col-xs-6 col-lg-5">
            <h1>{{ucfirst(trans('educal.calendar'))}}</h1>
        </div>
        <div class="col-xs-6 col-lg-5">

            @if(Sentry::getUser()->hasAnyAccess(array('school','event')))
            <a type="button" class="btn btn-default btn-lg btn-educal-warning pull-right" href="{{route
('event.create')}}" id="addEvent">
              <i class="fa fa-plus"></i> Add event
            </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10">
            <div id="calendar"></div>
            <div id="preloader">Loading...</div>
            <br>
        </div>
    </div>
</div>
<div id="content-bg"></div>

<!-- Event detail Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModal" aria-
     hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <h1 id="eventTitle"></h1>
                <p id="eventDescription" class="lead"></p>
                <p><strong>From:</strong> <span id="eventStart"></span></p>
                <p id="eventEnds"><strong>Until:</strong> <span id="eventEnd"></span></p>
                @if(Sentry::getUser()->hasAnyAccess(array('school','event')))
                <a type="button" class="btn btn-default btn-educal-warning" href="" id="editEvent">
                  <i class="fa fa-edit"></i> Edit
                </a>
                @endif
                <a type="button" class="btn btn-default btn-educal-primary" href="" id="icalEvent">
                  <i class="fa fa-share"></i> Export to calendar
                </a>
                @if(Sentry::getUser()->hasAnyAccess(array('school','event')))
                <a type="button" class="btn btn-default btn-educal-danger pull-right" href="" id="deleteEvent">
                  <i class="fa fa-times-circle"></i> Delete
                @endif
              </a>
            </div>
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