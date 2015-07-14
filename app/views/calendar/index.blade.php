@extends('layout.master-app')

@section('header')
{{ HTML::style("bower_components/fullcalendar/dist/fullcalendar.min.css") }}
{{ HTML::style("bower_components/datetimepicker/jquery.datetimepicker.css") }}
{{ HTML::style("css/calendar.css") }}
<script type="text/javascript">
    var org = {{ $school; }};
    var user = {{ $user; }};
</script>
@stop

@section('nav')
<section>
  <button type="button" name="button">School</button>
  <button type="button" name="button">Leerjaar</button>
  <input type="checkbox" value="1" id="y1">
  <label for="y1">1</label>
  <input type="checkbox" value="1" id="y2">
  <label for="y2">2</label>
  <input type="checkbox" value="1" id="y3">
  <label for="y3">3</label>
  <input type="checkbox" value="1" id="y4">
  <label for="y4">4</label>
  <input type="checkbox" value="1" id="y5">
  <label for="y5">5</label>
  <input type="checkbox" value="1" id="y6">
  <label for="y6">6</label>
  <button type="button" name="button">Klassen</button>
  <input type="checkbox" value="1" id="c1">
  <label for="c1">1A</label>
  <input type="checkbox" value="1" id="c2">
  <label for="c2">1B</label>
  <input type="checkbox" value="1" id="c3">
  <label for="c3">1C</label>
</section>
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
          @if(Sentry::getUser()->hasAnyAccess(['admin','editor']))
          <a type="button" class="btn btn-default btn-educal-warning" href="" id="editEvent">
            <i class="fa fa-edit"></i> {{ucfirst(trans('educal.editevent'))}}
          </a>
          @endif
          <a type="button" class="btn btn-default btn-educal-primary" href="" id="icalEvent">
            <i class="fa fa-share"></i> {{ucfirst(trans('educal.export'))}}
          </a>
          @if(Sentry::getUser()->hasAnyAccess(array('admin','editor')))
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
    <label for="" class="hidden">Event naam</label>
    <input class="input" type="text" placeholder="Event naam">
  </p>
  <div class="divider-btm dtpicker">
    <label for="d1">Tijd</label>
    <div class="dtpicker-dt dtpicker-start">
      <input class="input" type="date" placeholder="date">
      <input class="input" type="time" placeholder="time">
    </div>
    <div class="dtpicker-dt dtpicker-end">
      <input class="input" type="date" placeholder="date">
      <input class="input" type="time" placeholder="time">
    </div>
  </div>
  <p class="divider-btm">
    <input class="input" type="text" placeholder="Locatie">
  </p>
  <p class="divider-btm">
    <label class="checkbox-minimal">
      <input type="checkbox"> Herhaal event
    </label>
  </p>
  <p class="divider-btm event-description">
    <label for="description">Omschrijving</label>
    <input class="input" type="text" id="desctiption" placeholder="Voeg omschrijving toe">
  </p>
  <div>
    <label class="radio-minimal">
      <input type="radio" name="t"> School
    </label>
    <div class="select-year">
      <label class="radio-minimal">
        <input type="radio" name="t"> Leerjaar:
      </label>
      <span>1</span>
      <span>2</span>
      <span>3</span>
      <span>4</span>
      <span>5</span>
      <span>6</span>
      <span>7</span>
    </div>
    <label class="radio-minimal">
      <input type="radio" name="t"> Klas
    </label>
    <button type="button" name="button">Toevoegen</button>
  </div>
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
@endif {{ HTML::script('js/calendar.js') }}

{{ HTML::script('js/app.js') }}
@stop
