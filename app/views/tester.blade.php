@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-md-10">
        <div id="full-year" class="box"></div>
      </div>
    </div>
  </div>
@stop

@section('footerScript')
    <script type="text/javascript" src="js/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.11.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
    <script type="text/javascript" src="js/multical.js"></script>
{{ HTML::script('js/app.js') }}
@stop