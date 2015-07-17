@extends('layout.master-app')

@section('header')
{{ HTML::style("bower_components/fullcalendar/dist/fullcalendar.min.css") }}
{{ HTML::style("bower_components/datetimepicker/jquery.datetimepicker.css") }}
<script type="text/javascript">
    var org = {{ $school }};
    var user = {{ $user }};
    var root = {{ $root }};
    var calendars = {{ $calendars }};
    var urls = {{ json_encode([
      'events' => route('api.events')
    ]) }};
</script>
@stop

@section('nav')
<section class="navcals">

  @foreach($calendars_raw as $cal)
    @if(!$cal->id == $root->id)
    <p>
      <button type="button" name="button">Alleen schoolkalender</button>
    </p>
    @elseif($cal->parent_id == $root->id)
    <button class="level--1" type="button" style="border-color:{{$cal->color}}" data-cal="{{$cal->id}}">{{$cal->name}}</button>
    @else
    <label class="level--0" style="border-color:{{$cal->color}};display:none;" data-cal="{{$cal->id}}" data-parent="{{$cal->parent_id}}">
      <input type="checkbox">{{$cal->name}}
    </label>
    @endif
  @endforeach

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

<div class="hidden new-event">
  <form>
    <p class="divider-btm">
      <label class="has-input">
        Titel
        <input class="input input-title" type="text" placeholder="Voeg een titel voor het event toe" required>
      </label>
    </p>
    <div class="divider-btm dtpicker">
      <label for="d1">Tijd</label>
      <div class="dtpicker-dt dtpicker-start">
        <input class="input input-date d1" type="text" placeholder="date">
        <input class="input input-time t1" type="text" placeholder="time">
      </div>
      <svg height="60" stroke="#ccc" width="60" viewBox="0 0 30 60" xmlns="http://www.w3.org/2000/svg">
        <line x2="30" y2="30" />
        <line x2="30" y1="60" y2="30" />
      </svg>
      <div class="dtpicker-dt dtpicker-end">
        <input class="input input-date d2" type="text" placeholder="date">
        <input class="input input-time t2" type="text" placeholder="time">
      </div>
    </div>
    <p class="divider-btm">
      <label class="has-input">
        Locatie
        <input class="input input-location" type="text" placeholder="Voeg een locatie toe" required>
      </label>
    </p>
    <p class="divider-btm">
      <label class="has-input">
        Omschrijving
        <textarea class="input input-descr" type="text" id="description" placeholder="Voeg omschrijving toe" required></textarea>
      </label>
    </p>
    <p class="divider-btm">
      <label class="has-input" for="input-cals">
        Kalender {{ Form::select('calendar',$editableCalendars,null,array('class' => "input input-cals")); }}
      </label>
    </p>
    <p class="btnbar">
      <button type="button" class="btn-danger" onclick="editor.remove()">Verwijderen</button>
      <button type="button" class="btn-cancel" onclick="editor.close()">Annuleren</button>
      <button type="submit" class="btn-success" name="create">Toevoegen</button>
    </p>
  </form>
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
{{ HTML::script('js/editor.js') }}
{{ HTML::script('js/calendar.js') }}

{{ HTML::script('js/app.js') }}
@stop
