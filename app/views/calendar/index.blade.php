@extends('layout.master-app')

@section('header')
{{ HTML::style("bower_components/fullcalendar/dist/fullcalendar.min.css") }}
{{ HTML::style("bower_components/datetimepicker/jquery.datetimepicker.css") }}
<script type="text/javascript">
    var org = {{ $school }};
    var user = {{ $user }};
    var calendars = {{ $calendars }};
    var urls = {{ json_encode([
      'events' => route('api.events')
    ]) }};
</script>
@stop

@section('nav')
<section class="nav-cals">
</section>
@if(Sentry::check() && Sentry::getUser()->hasAccess('admin'))
<ul>
  <li>
    <a href="{{ route('admin.dashboard', [$org->slug]) }}"
    {{ Route::currentRouteName()=='school.index' ? ' class="active"' : '' }}>
      <i class="glyphicon glyphicon-folder-close"></i>
      Dashboard
    </a>
  </li>
</ul>
@endif
@stop

@section('content')
  <div id="calendar"></div>

  <!-- Event detail Modal -->
  <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModal" aria- hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <p class="modal-title">{{ucfirst(trans('educal.eventdetails'))}}</p>
        </div>
        <div class="modal-body"><span><b>Calendar: </b></span>
          <span id="groupName"></span>
          <h1 id="eventTitle"></h1>
          <p id="eventDescription" class="lead"></p>
          <p>
            <strong>{{ucfirst(trans('educal.starts'))}}:</strong>
            <span id="eventStart"></span>
          </p>
          <p id="eventEnds">
            <strong>{{ucfirst(trans('educal.ends'))}}:</strong>
            <span id="eventEnd"></span>
          </p>
            @if(Sentry::check() && Sentry::getUser()->hasAnyAccess(['admin','editor']))
          <a type="button" class="btn btn-default btn-educal-warning" href="" id="editEvent">
            <i class="fa fa-edit"></i> {{ucfirst(trans('educal.editevent'))}}
          </a>
          @endif
          <a type="button" class="btn btn-default btn-educal-primary" href="" id="icalEvent">
            <i class="fa fa-share"></i> {{ucfirst(trans('educal.export'))}}
          </a>
            @if(Sentry::check() && Sentry::getUser()->hasAnyAccess(array('admin','editor')))
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
        <div class="modal-header">
          {{ucfirst(trans('educal.confirmation'))}}
        </div>
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

<div class="hidden new-event">
<p class="divider-btm">
  <label class="has-input">
    Titel
    <input class="input input-title" type="text" placeholder="Voeg een titel voor het event toe">
  </label>
</p>
  <div class="divider-btm dtpicker">
    <label for="d1">Tijd</label>
    <div class="dtpicker-dt dtpicker-start">
      <input class="input input-date d1" type="text" placeholder="date">
      <input class="input input-time t1" type="text" placeholder="time">
    </div>
    <div class="dtpicker-dt dtpicker-end">
      <input class="input input-date d2" type="text" placeholder="date">
      <input class="input input-time t2" type="text" placeholder="time">
    </div>
  </div>
  <p class="divider-btm">
    <label class="has-input">
      Locatie
      <input class="input input-location" type="text" placeholder="Voeg een locatie toe">
    </label>
  </p>
  <p class="divider-btm">
    <label class="has-input">
      Omschrijving
      <textarea class="input input-descr" type="text" id="description" placeholder="Voeg omschrijving toe"></textarea>
    </label>
  </p>
  <p class="divider-btm">
    <label class="has-input" for="cal-select">
      Kalender
      {{ Form::select('calendar',$editableCalendars,null,array('class' => "input input-cals")); }}
    </label>
  </p>
  <p class="btnbar">
    <button type="button" class="btn-danger" name="button">Verwijderen</button>
    <button type="button" class="btn-cancel" name="button">Annuleren</button>
    <button type="button" class="btn-success" name="button">Toevoegen</button>
  </p>
</div>
@stop

@section('footerScript')
{{ HTML::script("js/build/production.min.js") }}
{{ HTML::script("bower_components/fullcalendar/dist/fullcalendar.min.js") }}
@if(Session::get('lang') == 'nl')
  {{ HTML::script("bower_components/fullcalendar/dist/lang/nl.js") }}
@elseif(Session::get('lang') == 'fr')
  {{ HTML::script("bower_components/fullcalendar/dist/lang/fr.js") }}
@elseif(Session::get('lang') == 'de')
  {{ HTML::script("bower_components/fullcalendar/dist/lang/de.js") }}
@endif

{{ HTML::script('js/calnav.js') }}
{{ HTML::script('js/api.js') }}
{{ HTML::script('js/render.js') }}
{{ HTML::script('js/editor.js') }}
{{ HTML::script('js/calendar.js') }}

{{ HTML::script('js/app.js') }}
@stop
