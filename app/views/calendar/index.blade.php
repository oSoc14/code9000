@extends('layout.master-app')

@section('header')
    {{ HTML::style("bower_components/fullcalendar/dist/fullcalendar.min.css") }}
    {{ HTML::style("bower_components/datetimepicker/jquery.datetimepicker.css") }}
    {{ HTML::style("css/calendar.css") }}
    <script type="text/javascript">
        var org = {{ $school }};
        var user = {{ $user }};
        var root = {{ $root }};
        var calendars = {{ json_encode($calendars,JSON_NUMERIC_CHECK) }};
        var urls = {{ json_encode([
      'events' => route('api.event.handle')
    ],JSON_NUMERIC_CHECK) }};
    </script>
@stop

@section('nav')
    <section class="navcals">

        @foreach($calendars as $cal)
            @if(!$cal->id == $root->id)
                <p>
                    <button type="button" name="button">Alleen schoolkalender</button>
                </p>
            @elseif($cal->parent_id == $root->id)
                <button class="level--1" type="button" data-cal="{{$cal->id}}">
                    {{$cal->name}}
                    <svg height="20" stroke="#ccc" width="16" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg"
                         fill="none" stroke-width="2" stroke-linecap="round">
                        <path d="M1 7 L8 14 L15 7"></path>
                    </svg>
                </button>
            @else
                <label class="level--0" style="display:none;" data-color="{{$cal->color}}" data-cal="{{$cal->id}}"
                       data-parent="{{$cal->parent_id}}">
                    <span class="checkbox"><input type="checkbox"></span>{{$cal->name}}
                </label>
            @endif
        @endforeach

    </section>
@stop

@section('content')
    <div id="calendar">
        <div class="top-right">
            <button id="btn-export" data-base="{{ route('export.index', [$org->slug, '']) }}"
                    class="btn btn--inline btn-primary @if(Sentry::check()) btn-inverse @endif">
                Exporteer deze kalender
            </button>
            @if(Sentry::check())
                <button id="btn-addevent" class="btn btn--inline btn-primary">Voeg event toe</button>
            @endif

        </div>
    </div>

    <div class="hidden read-event-template">
        <div class="read-event">
            <button type="button" onclick="editor.close()" class="close">&times;</button>
            <h1 class="read-title divider-btm"></h1>

            <p class="divider-btm">
                <time class="read-dt"></time>
            </p>
            <p class="read-location divider-btm"></p>

            <p class="read-descr divider-btm"></p>

            <p class="read-cal divider-btm"></p>
        </div>
    </div>
    <div class="hidden new-event">
        <form>
            <p class="divider-btm">
                <label class="has-input">
                    <span>Titel</span>
                    <input class="input input-title" type="text" placeholder="Voeg een titel voor het event toe"
                           required>
                </label>
            </p>

            <div class="divider-btm dtpicker">
                <label for="d1">Tijd</label>

                <div class="dtpicker-dt dtpicker-start">
                    <input class="input input-date d1" type="text" placeholder="date">
                    <input class="input input-time t1" type="text" placeholder="time">
                </div>
                <svg height="61" stroke="#ccc" width="35" viewBox="0 0 30 60" xmlns="http://www.w3.org/2000/svg">
                    <line x2="30" y2="30"/>
                    <line x2="30" y1="60" y2="30"/>
                </svg>
                <div class="dtpicker-dt dtpicker-end">
                    <input class="input input-date d2" type="text" placeholder="date">
                    <input class="input input-time t2" type="text" placeholder="time">
                </div>
            </div>
            <p class="divider-btm">
                <label class="has-checkbox">
                    <input type="checkbox" class="input-allday" value="1"> Hele dag
                </label>
            </p>

            <p class="divider-btm">
                <label class="has-input">
                    Locatie
                    <input class="input input-location" type="text" placeholder="Voeg een locatie toe">
                </label>
            </p>

            <p class="divider-btm">
                <label class="has-input">
                    Omschrijving
                    <textarea class="input input-descr" type="text" id="description" placeholder="Voeg omschrijving toe"
                            ></textarea>
                </label>
            </p>

            <p class="divider-btm">
                <label class="has-input" for="input-cals">
                    Voor
                    wie {{ Form::select('calendar',$editableCalendars,null,array('class' => "input input-cals")); }}
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
