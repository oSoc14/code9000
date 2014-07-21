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
    <div class="row first-row">
        <div class="col-xs-6 col-lg-5">
            <h1>{{ucfirst(trans('educal.calendar'))}}</h1>
        </div>
        <div class="col-xs-6 col-lg-5">

            @if(Sentry::getUser()->hasAnyAccess(array('school','event')))
            <a type="button" class="btn btn-default btn-lg btn-educal-warning pull-right" href="{{route
('event.create')}}" id="addEvent">
              <i class="fa fa-plus"></i> {{ucfirst(trans('educal.addevent'))}}
            </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10">
            <div id="calendar"></div>
            <div id="preloader">{{ucfirst(trans('educal.loading'))}}</div>
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
                <p class="modal-title">{{ucfirst(trans('educal.eventdetails'))}}</p>
            </div>
            <div class="modal-body">
                <h1 id="eventTitle"></h1>
                <p id="eventDescription" class="lead"></p>
                <p><strong>{{ucfirst(trans('educal.starts'))}}:</strong> <span id="eventStart"></span></p>
                <p id="eventEnds"><strong>{{ucfirst(trans('educal.ends'))}}:</strong> <span id="eventEnd"></span></p>
                @if(Sentry::getUser()->hasAnyAccess(array('school','event')))
                <a type="button" class="btn btn-default btn-educal-warning" href="" id="editEvent">
                  <i class="fa fa-edit"></i> {{ucfirst(trans('educal.editevent'))}}
                </a>
                @endif
                <a type="button" class="btn btn-default btn-educal-primary" href="" id="icalEvent">
                  <i class="fa fa-share"></i> {{ucfirst(trans('educal.export'))}}
                </a>
                @if(Sentry::getUser()->hasAnyAccess(array('school','event')))
                <a type="button" class="btn btn-default btn-educal-danger pull-right" data-toggle="modal" data-target="#confirm-delete" href="#" data-href="" title="Remove" id="deleteEvent">
                    <i class="fa fa-times-circle"></i> {{ucfirst(trans('educal.deleteevent'))}}
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
              {{ucfirst(trans('educal.confirmationmsg'))}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-educal-warning" data-dismiss="modal">{{ucfirst(trans('educal.cancel'))}}</button>
                <a href="#" class="btn btn-educal-danger"><i class="fa fa-times-circle"></i> {{ucfirst(trans('educal.confirmation'))}}</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('footerScript')
{{ HTML::script("packages/fullcalendar/lib/moment.min.js") }}
{{ HTML::script("packages/fullcalendar/fullcalendar.min.js") }}
@if(Session::get('lang') == 'nl')
  {{ HTML::script("packages/fullcalendar/lang/nl.js") }}
@elseif(Session::get('lang') == 'fr')
  {{ HTML::script("packages/fullcalendar/lang/fr.js") }}
@elseif(Session::get('lang') == 'de')
  {{ HTML::script("packages/fullcalendar/lang/de.js") }}
@endif
{{ HTML::script('js/calendar.js') }}
{{ HTML::script('js/app.js') }}
@stop