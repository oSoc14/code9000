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
  <!-- FontAwesome icons -->
  {{ HTML::style("//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css") }}
  <!-- Google Webfont -->
  {{ HTML::style("http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700") }}
  <!-- Global styles for this template -->
  @yield('header')
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta property="og:title" content="">
  <meta property="og:site_name" content="">
  <meta property="og:description" content="">
  <meta property="og:image" content="{{ asset('') }}">
  <link rel="shortcut icon" href="favicons/favicon.ico">
  <link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-touch-icon-152x152.png">
  <link rel="icon" type="image/png" href="favicons/favicon-196x196.png" sizes="196x196">
  <link rel="icon" type="image/png" href="favicons/favicon-160x160.png" sizes="160x160">
  <link rel="icon" type="image/png" href="favicons/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="favicons/favicon-16x16.png" sizes="16x16">
  <link rel="icon" type="image/png" href="favicons/favicon-32x32.png" sizes="32x32">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="favicons/mstile-144x144.png">
  <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ HTML::script("js/html5shiv.js") }}
    {{ HTML::script("js/respond.min.js") }}
    <![endif]-->
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
</head>
<body>
  <!-- Top navbar -->
  <div class="navbar navbar-default navbar-fixed-top" id="navbar-educal" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#sidebar-wrapper">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <h1 id="navbar-logo"><span class="hidden">EduCal</span></h1>
      </div>
    </div>
  </div>

  <div id="main-wrapper">

    <!-- Navigation sidebar -->
    <div id="sidebar" class="sidebar-wrapper">
      <div class="logo-container">
        <h1 id="navbar-logo-sidebar"><span class="hidden">EduCal</span></h1>
      </div>
      <ul class="panel-group accordions" id="accordion">
        <li class="panel-heading">
          <a href="{{ route('calendar.index') }}">
            <p class="panel-title {{ Route::currentRouteName()=='calendar.index' ? 'active' : '' }}">
              <i class="fa fa-calendar fa-lg"></i>
              {{ucfirst(trans('educal.calendar'))}}
            </p>
          </a>
        </li>
        @if(Sentry::getUser()->hasAccess('school'))
        <li class="panel-heading">
          <a href="{{ route('school.index') }}">
            <p class="panel-title {{ (Route::currentRouteName()=='school.index' ? 'active' : '') }}">
              <i class="fa fa-bank fa-lg"></i>
              {{ucfirst(trans('educal.schools'))}}
            </p>
          </a>
        </li>
        @endif
        @if(Sentry::getUser()->hasAnyAccess(array('school','user')))
        <li class="panel-heading">
          <a href="{{ route('user.index') }}">
            <p class="panel-title {{ (Route::currentRouteName()=='user.index' ? 'active' : '') }}">
              <i class="fa fa-users fa-lg"></i>
              {{ucfirst(trans('educal.users'))}}
            </p>
          </a>
        </li>
        @endif
        @if(Sentry::getUser()->hasAnyAccess(array('school','group')))
        <li class="panel-heading">
          <a href="{{ route('group.index') }}">
            <p class="panel-title  {{ Route::currentRouteName()=='group.index' ? 'active' : '' }}">
              <i class="fa fa-rocket fa-lg"></i>
              {{ucfirst(trans('educal.groups'))}}
            </p>
          </a>
        </li>
        @endif
        <div class="bottom-options">
          <li class="panel-heading">
            <a href="about">
              <p class="panel-title">
                <i class="fa fa-question-circle fa-lg"></i>
                {{ucfirst(trans('educal.about'))}}
              </p>
            </a>
          </li>
          <li class="panel-heading">
            <a href="{{ route('settings') }}">
              <p class="panel-title">
                <i class="fa fa-cogs fa-lg"></i>
                {{ucfirst(trans('educal.settings'))}}
              </p>
            </a>
          </li>
          <li class="panel-heading">
            <a href="{{ route('user.logout') }}">
              <p class="panel-title">
                <i class="fa fa-sign-out fa-lg"></i>
                {{ucfirst(trans('educal.logout'))}}
              </p>
            </a>
          </li>
          <li class="panel-heading">
              <a href="https://docs.google.com/forms/d/1-DBq0c2lmOEmJAJZ89hgCPSf4RjObr4XffMBajPEPtI/viewform?usp=send_form">
                  <p class="panel-title">
                    <i class="fa fa-send fa-lg"></i>
                      Feedback
                  </p>
              </a>
          </li>
        </div>
        </li>
      </ul>
      @if(Sentry::check())
      <div id="userinfo-wrapper">
        <strong>Currently logged in as:</strong><br>
        @if(Sentry::getUser()->first_name != "")
        {{Sentry::getUser()->first_name}} {{Sentry::getUser()->last_name}}<br>
        @else
        {{Sentry::getUser()->email}}<br>
        @endif
        @if(Sentry::getUser()->school != null)
        {{Sentry::getUser()->school->name}}
        @endif
      </div>
      @endif
    </div>

    <!-- Content -->
    <div id="content-wrapper">
      @yield('content')
    </div><!-- / #content-wrapper -->

  </div><!-- / #main-wrapper -->
  <div id="backdrop" class="hidden"></div>


{{ HTML::script("js/jquery-1.11.1.min.js") }}
{{ HTML::script("js/moment.js") }}
{{ HTML::script("js/bootstrap.min.js") }}
{{ HTML::script("js/jquery.datetimepicker.js") }}
@yield('footerScript')

</body>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-42559847-18', 'auto');
    ga('send', 'pageview');

</script>

</html>