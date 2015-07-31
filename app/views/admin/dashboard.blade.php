@extends('layout.master-app')

@section('header')
{{ HTML::style("css/admin.css") }}
@stop

@section('content')
<div>
  <h1>Dashboard</h1>

  @include('admin/navbar')

  <p>
    Publieke kalendar URL:
  </p>

  <a href="{{ route('orgs.index', [$org->slug]) }}"><h2>{{ route('orgs.index', [$org->slug]) }}</h2></a>

  <p>
    Schoolnaam: {{ $org->name }}<br>
    Locatie: {{ $org->city }}
  </p>

</div>
@stop
