<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{ HTML::entities('educal') }}</title>

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
<nav id="topnav">
    <div class="container">
        <a class="mobile-header" id="toggle-menu-collapse" href="#"><img class="nav"
                                                                         src="{{ asset('images/landing/logo_white.png') }}"
                                                                         alt="Educal"><i
                    class="fa fa-bars right fa-2x"></i>
        </a>
        <ul class="nav" id="menu-collapse">
            <li class="brand"><a class="nav brand" href="#top"><img class="nav"
                                                                    src="{{ asset('images/landing/logo_white.png') }}"
                                                                    alt="Educal"></a></li>
            <li><a class="nav " href="#werking">Wat is Educal?</a></li>
            <li><a class="nav " href="#waarom">Waarom Educal?</a></li>
            <li><a class="nav " href="#start">Start met Educal</a></li>
            <li class="right"><a class="nav-login" href="{{route('user.login')}}">Log in</a></li>
        </ul>
    </div>
</nav>

<section class="page splash">
    <div class="container">
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <h1>Plan.<br />Organiseer.<br />Deel.</h1>

            <a href="#werking" class="btn btn-default">Leer meer over Educal</a>
            <a href="{{route('user.login')}}" class="btn btn-subtle">Log in</a>

            {{-- <h2>Zoek mijn school</h2>

            <form class="splash-search">
                <label for="schoolsearch" class="sr-only">Naam van school</label>
                <input type="text" id="schoolsearch" placeholder="Vb: het Koninklijk Atheneum Gent">
                <input type="submit" id="schoolsearchsubmit" value="&#xf002;">
            </form> --}}
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 hidden-xs">
            <img src="{{ asset('images/landing/splashimg.jpg') }}" class="splash-img">
        </div>
        <a class="arrow-readon" href="#werking"><i class="fa fa-angle-down fa-4x"></i></a>
    </div>
</section>

<section class="page" id="werking">
    <div class="container">
        <div class="col-md-12">
            <h2>Wat is Educal?</h2>
        </div>
        <div class="col-md-5">
            <ul>
                <li>
                    <strong>Voor scholen</strong><br>
                    Organiseer en plan evenementen op school-, jaar- en klasniveau met Educal. Deel de kalender met ouders en hun kinderen. Blije ouders, blije kinderen, top organisatie!
                </li>
                <li>
                    <strong>Voor ouders</strong><br>
                    Bekijk alle activiteiten van je kind in een handig overzicht. Exporteer de activiteiten naar je eigen digitale agenda. Geef een zwembroek mee op zwemdag!
                </li>
            </ul>
            <p><a href="#waarom" title="Waarom Educal">Waarom Educal &rarr;</a></p>
        </div>
        <div class="col-md-7">
            <div class="video-16-9">
                <iframe src="https://player.vimeo.com/video/134952726?color=8fafc6&title=0&byline=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>

<section class="page" id="waarom">
    <div class="container">
        <div class="col-md-12 text-center">
            <h2>Voordelen van Educal</h2>
        </div>

        <div class=" col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-0 col-why">
            <img src="{{ asset('images/landing/checkmark.svg') }}" alt="checkmark">

            <h3>Makkelijk in gebruik</h3>

            <p>
                Zo gemakkelijk dat grootmoeder het kan gebruiken. Dat is het&nbsp;motto.
            </p>
        </div>

        <div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-0 col-why">
            <img src="{{ asset('images/landing/money.svg') }}" alt="checkmark">

            <h3>Gratis &amp; Open Source</h3>

            <p>
                De open source versie van Educal is 100% gratis, voor&nbsp;altijd.
            </p>
        </div>

        <div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-0 col-why">
            <img src="{{ asset('images/landing/link.svg') }}" alt="checkmark">

            <h3>Blijf verbonden</h3>

            <p>
                Educal altijd op zak: exporteer Educal naar een smartphone, tablet of&nbsp;computer.
            </p>
        </div>

        <div class="col-md-12 text-center">
            <p><a href="#start" title="Start met Educal">Start met Educal &rarr;</a></p>
        </div>
    </div>
</section>
<section class="page register" id="start">
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <h2>Start met Educal op mijn school</h2>

            <ol>
                <li>Start <strong>100% gratis</strong></li>
                <li><strong>Maak evenementen aan</strong> op school-, jaar- en klasniveau</li>
                <li><strong>Deel kalenders</strong> met ouders en leerkrachten</li>
            </ol>

            <a class="btn btn-primary btn-block has-help-text" href="{{ route('school.register') }}">Start nu gratis</a>
            <span class="help-text">na 2 minuten ben je aan de slag :-)</span>
        </div>
    </div>
</section>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
{{ HTML::script('js/landing.js') }}
</body>
