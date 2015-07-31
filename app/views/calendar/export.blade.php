@extends('layout.master-app')
@section('header')
    {{ HTML::style("css/admin.css") }}
@stop
@section('content')
    <section class="export-help">
        <img src="{{ asset('images/landing/logo_black.svg') }}" class="brand center-block">

        <h2>Bedankt om educal te gebruiken</h2>

        <p>Afhankelijk van het platform waarop je je kalender wenst te importeren, dien je ofwel de URL op te geven,
            ofwel het kalenderbestand te importeren. Hieronder vind je zowel een download link als URL naar de
            kalender.</p>
        <label for="url">Link naar de door jou gekozen agenda's:</label>
        <input name="url" type="text" value="{{ route('export.ics', [$org_slug,$calendars]) }}">

        <button class="btn btn-primary" onclick="location.href='{{ route('export.ics', [$org_slug,$calendars]) }}';">
            Klik hier
            om de agenda te downloaden
        </button>
        <div class="clearfix"></div>

        <h2>Importeer je agenda</h2>

        <h3 id="google">Google calendar en android</h3>

        <p>Voor zowel google calendar als android ga je naar <a
                    href="http://calendar.google.com">calendar.google.com</a>. Hier meld je je aan, en importeer je de
            kalender op basis van de link</p>
        <img src="{{ asset('images/export/googlecalendar.jpg') }}" class="screenshot">
        <img src="{{ asset('images/export/googlecalendar2.png') }}" class="screenshot">

        <h3 id="ios">iOS</h3>

        <p>
            Op iOS importeer je de kalender via de instellingen van je toestel. Ga naar Agenda, Anders, en klik op "voeg
            abbonement toe".
        </p>
        <img src="{{ asset('images/export/stap1.png') }}" class="screenshot screenshot-stap">
        <img src="{{ asset('images/export/stap2.png') }}" class="screenshot screenshot-stap">
        <img src="{{ asset('images/export/stap3.png') }}" class="screenshot screenshot-stap">
        <img src="{{ asset('images/export/stap4.png') }}" class="screenshot screenshot-stap">
        <img src="{{ asset('images/export/stap5.png') }}" class="screenshot screenshot-stap">
    </section>

@stop