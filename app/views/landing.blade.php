<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ HTML::entities('EduCal') }}</title>
  <!-- Bootstrap core CSS -->
  {{ HTML::style("css/bootstrap.min.css") }}
  {{ HTML::style("css/bootstrap-theme.min.css") }}
  <!-- Bootstrap Datepicker -->
  {{ HTML::style("css/jquery.datetimepicker.css") }}
  <!-- jQuery UI -->
  {{ HTML::style("css/jquery-ui.min.css") }}
  {{ HTML::style("css/jquery-ui.structure.min.css") }}
  {{ HTML::style("css/jquery-ui.theme.min.css") }}
  <!-- Global styles for this template -->
  {{ HTML::style("css/landing.css") }}
  @yield('header')
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta property="og:title" content="">
  <meta property="og:site_name" content="">
  <meta property="og:description" content="">
  <meta property="og:image" content="{{ asset('') }}">
  <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  {{ HTML::script("js/html5shiv.js") }}
  {{ HTML::script("js/respond.min.js") }}
  <![endif]-->
  <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
</head>
<body>

<div class="page-container">

  <div class="container-fluid">
    <div class="row introduction-container">
      <div class="col-xs-12 introduction-content">
        <h1>Welcome to <span class="hidden">EduCal</span></h1>
        <img src="images/logo_educal.png" alt="Logo" />
        <p class="lead">EduCal is an application for schools to easily create and manage calendars that can be shared with parents.</p>
        <div class="button-container">
          <a href="#" class="btn btn-lg btn-default btn-educal-secondary">Log in <span class="glyphicon glyphicon-log-in"></span></a> or
          <a href="#" class="btn btn-lg btn-default btn-educal-primary">Register <span class="glyphicon glyphicon-link"></span></a>
        </div>
      </div>
    </div>
  </div>

  <div class="container content-container">
    <div class="row content-container">
      <div class="col-xs-12 col-sm-12 col-md-4">
        <h2>About</h2>
        <p>Nowadays, parents have a lot of different calendars to keep track of for their children. <strong>EduCal</strong> is an interactive calendar management platform that allows schools (and others) to manage multiple calendars. These calendars can be downloaded by staff and parents as an iCal-file or pdf-file. This iCal-file can then be <strong>imported</strong> in their own agendas.</p>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <h2>Get started</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias asperiores, culpa distinctio doloremque earum eius ex illo laudantium nemo nihil odio praesentium quidem quos ratione rem tempore ullam ut. Amet.</p>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <h2>Contact</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias asperiores, culpa distinctio doloremque earum eius ex illo laudantium nemo nihil odio praesentium quidem quos ratione rem tempore ullam ut. Amet.</p>
      </div>
    </div>
  </div>

  <div class="container-fluid footer-container">
    <footer>
      &copy; OKFN Belgium<br>
      Created by team Code9000 during <a href="http://opensummerofcode.be">open Summer of Code 2014</a>.
    </footer>
  </div>

</div>

</body>