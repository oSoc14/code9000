<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>{{ HTML::entities('EduCal') }}</title>
  <!-- Google Webfont -->
  {{ HTML::style("http://fonts.googleapis.com/css?family=Roboto:400,500") }}
  <!-- Global styles for this template -->
  {{ HTML::style("css/calendar.css") }}
  <!-- Extra styles -->
  @yield('header')
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta property="og:title" content="">
  <meta property="og:site_name" content="">
  <meta property="og:description" content="">
  <meta property="og:image" content="{{ asset('') }}">
  <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}">
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-touch-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-touch-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-touch-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-touch-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-touch-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-touch-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-touch-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-touch-icon-152x152.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-196x196.png') }}" sizes="196x196">
  <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-160x160.png') }}" sizes="160x160">
  <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-96x96.png') }}" sizes="96x96">
  <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-16x16.png') }}" sizes="16x16">
  <link rel="icon" type="image/png" href="{{ asset('favicons/favicon-32x32.png') }}" sizes="32x32">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{ asset('favicons/mstile-144x144.png') }}">
  <meta name="msapplication-config" content="{{ asset('favicons/browserconfig.xml') }}">
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    {{ HTML::script("js/html5shiv.js") }}
    {{ HTML::script("js/respond.min.js") }}
    <![endif]-->
  <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
</head>

<body>
  <nav class="nav nav-main">
    <h1>Trappenhuis</h1>
    <section class="flex--1">
      @section('nav')
      <ul class="nav-list">
        <li>
          <a href="{{ route('orgs.index',[$org->slug]) }}" {{ Route::currentRouteName()=='calendar.redirect' ? ' class="active"' : '' }}>
            <i class="glyphicon glyphicon-calendar"></i>
            {{ucfirst(trans('educal.calendar'))}}
          </a>
        </li>
        @if(Sentry::check() && Sentry::getUser()->hasAccess('admin'))
        <li>
          <a href="{{ route('admin.dashboard',[$org->slug]) }}"
          {{ Route::currentRouteName()=='school.index' ? ' class="active"' : '' }}>
            <i class="glyphicon glyphicon-folder-close"></i>
            Dashboard
          </a>
        </li>
        @endif
      </ul>
      @show
    </section>
    @if(Sentry::check())
    <section class="flex--0">
      <ul class="nav-list">
        <li><a href="{{ route('help') }}">Help!</a></li>
        <li><a href="{{ route('user.edit') }}">Profiel</a></li>
          @if(Sentry::check())
              <li>
                  <a href="{{ route('user.logout') }}"
                          {{ Route::currentRouteName()=='school.index' ? ' class="active"' : '' }}>
                      <i class="glyphicon glyphicon-folder-close"></i>
                      Logout
                  </a>
              </li>
          @endif
      </ul>
      <p class="text-muted">
        @if(Sentry::getUser()->first_name != "")
        <span>{{Sentry::getUser()->first_name}} {{Sentry::getUser()->last_name}}</span>
        @else
        <span>{{Sentry::getUser()->email}}</span>
        @endif
        @if(Sentry::getUser()->school != null)
        <br><small>{{Sentry::getUser()->school->name}}</small>
        @endif
      </p>
    </section>
    @endif
  </nav>
  <main class="main">
    @yield('content')
  </main>
  <div id="backdrop" class="hidden"></div>
  @yield('footerScript')
  <script>
    (function (i, s, o, g, r, a, m) {
      i['GoogleAnalyticsObject'] = r;
      i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
              }, i[r].l = 1 * new Date();
      a = s.createElement(o),
              m = s.getElementsByTagName(o)[0];
      a.async = 1;
      a.src = g;
      m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-42559847-18', 'auto');
    ga('send', 'pageview');
  </script>

</body>

</html>
