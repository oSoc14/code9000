@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-md-10">
  <h1>Over educal</h1>
      <p>
        Ouders kunnen tegenwoordig veel verschillende kalenders hebben om de activiteiten van hun kinderen bij te houden. <strong>Educal</strong> is een interactief kalendermanagement platform waarmee scholen (maar ook andere organisaties) meerdere kalenders kunnen beheren.</p><p>Deze kalenders kunnen daarna gedownload worden door leerkrachten en ouders als een iCal of PDF-bestand. Dit iCal bestand kan met 1 klik <strong>geïmporteerd</strong> worden in hun eigen digitale kalender.
      </p>
  <h3>Credit</h3>
      <p>Dit platform is ontwikkeld door het Code9000 team als onderdeel van <a href="http://summerofcode.be">open Summer of Code 2014</a>, georganiseerd door <a href="http://www.okfn.be">OKFN Belgium</a>.</p>
      <p>Educal is mede mogelijk gemaakt door <a href="http://www.digipolis.be">Digipolis</a> en de <a href="http://www.gent.be">Stad Gent</a>.</p>
      <p>Authors: Bjorn Van Acker, Sander Meert, Nick Denys<br>
        &copy; OKFN Belgium</p>
      </div>
    </div>
  </div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop
