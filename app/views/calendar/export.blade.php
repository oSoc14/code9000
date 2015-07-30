@extends('layout.master-app')
@section('header')
    {{ HTML::style("css/calendar.css") }}
@stop
@section('content')
Kopieer deze link naar je agenda:
{{ route('export.ics', [$org_slug,$calendars]) }}<br>
<a href="{{ route('export.ics',[$org_slug,$calendars]) }}">Klik hier als je het bestand wilt opslaan</a>
@stop