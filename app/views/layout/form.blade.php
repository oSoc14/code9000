<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>{{ HTML::entities('educal') }}</title>
    <!-- Google Webfont -->
    {{ HTML::style("http://fonts.googleapis.com/css?family=Roboto:400,500") }}
            <!-- Global styles for this template -->
    {{ HTML::style("css/form.css") }}
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

<body id="top">
<div class="background"></div>
<nav id="topnav">
    <div class="container">
        <a class="mobile-header" id="toggle-menu-collapse" href="#"><img class="nav"
                                                                         src="{{ asset('images/landing/logo_white.png') }}"
                                                                         alt="educal"><i
                    class="fa fa-bars right fa-2x"></i>
        </a>
        <ul class="nav" id="menu-collapse">
            <li class="brand"><a class="nav brand" href="{{URL::to('/')}}"><img class="nav"
                                                                    src="{{ asset('images/landing/logo_white.png') }}"
                                                                    alt="educal"></a></li>
            <li class="right"><a class="nav-login" href="{{ route('user.login') }}">Log
                    in</a></li>
        </ul>
    </div>
</nav>

@yield('header')

<section class="form educal-form">
    @if($errors->has('errorMessage'))
        <div class="alert alert-danger" role="alert">
            <strong>{{ucfirst(trans('educal.errors'))}}</strong>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ ucfirst($message)}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('form')
</section>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
{{ HTML::script('js/landing.js') }}
</body>
