<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{ HTML::entities('EduCal') }}</title>

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
        <ul class="nav">
            <li class="brand"><a class="nav brand" href="#top"><img class="nav" src="images/landing/logo_white.png"
                                                                    alt="educal"></a></li>
            <li><a class="nav " href="#werking">Hoe werkt het</a></li>
            <li><a class="nav " href="#waarom">Waarom educal</a></li>
            <li><a class="nav " href="#school">Educal voor mijn school</a></li>
        </ul>
        <ul class="nav right">
            <li><a class="nav-login" href="#login">Log in</a></li>
        </ul>
    </div>
</nav>

<section class="page splash">
    <div class="container">
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <h1>Jouw school kalender</h1>

            <h2>Zoek je school</h2>

            <form class="splash-search">
                <label for="schoolsearch" class="sr-only">Naam van school</label>
                <input type="text" id="schoolsearch" placeholder="Vb: het atheneum vilvoorde">
                <input type="submit" id="schoolsearchsubmit" value="&#xf002;">
            </form>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 hidden-xs">
            <img src="/images/landing/splashimg.jpg" class="splash-img">
        </div>
        <a class="arrow-readon" href="#werking"><i class="fa fa-angle-down fa-4x"></i></a>
    </div>
</section>

<section class="page" id="werking">
    <div class="container">
        <div class="col-md-3">
            <h2>Wat is educal?</h2>

            <ul>
                <li>
                    Educal is een kalender enkel de activiteiten waar jij geïnteresseerd in bent in een oogopslag laat
                    zien.
                </li>
                <li>
                    Exporteer de kalender naar je eigen agenda zodat je altijd het beste op de hoogte bent van de
                    activiteiten van je kind!
                </li>
            </ul>
        </div>

        <div class="col-md-8 col-md-offset-1">
            <a class="col-md-6 btn btn-switch btn-switch-active" href="#">Ik ben een leerkracht</a>
            <a class="col-md-6 btn btn-switch" href="#">Ik ben een ouder</a>
            <iframe style="height:400px; width:100%;"
                    src="https://www.youtube.com/embed/E1ODC0W9g1s?list=PLa2a9FJY91_0x1s4eq6mf9b91m3Pahv0G"
                    frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</section>

<section class="page" id="waarom">
    <div class="container">
        <div class="col-md-4 col-md-offset-8">
            <h2>Waarom educal</h2>
        </div>

        <div class="col-md-4 col-why">
            <img src="images/landing/checkmark.svg" alt="checkmark">

            <h3>Makkelijk in gebruik</h3>

            <p>
                Educal is enorm gebruiksvriendelijk
            </p>
        </div>

        <div class="col-md-4 col-why">
            <img src="images/landing/money.svg" alt="checkmark">

            <h3>Gratis</h3>

            <p>
                Educal is 100% gratis
            </p>
        </div>

        <div class="col-md-4 col-why">
            <img src="images/landing/link.svg" alt="checkmark">

            <h3>Blijf verbonden</h3>

            <p>
                Uw kalender altijd op zak: U kan uw kalender exporteren en toevoegen aan u eigen agenda, of importeren
                op u gsm
            </p>
        </div>
    </div>
</section>
<section class="page" id="school">
    <div class="container">
        <div class="col-md-4">
            <h2>Educal voor mijn school?</h2>
        </div>

        <div class="col-md-6 col-md-offset-2">
            <ol>
                <li>Registreer via onderstaande knop</li>
                <li>Beheer je school via het dashboard</li>
                <li>Maak events aan op school-, jaar- en klasniveau</li>
            </ol>

            <button class="btn btn-info align-ol">Registreer</button>
        </div>
    </div>
</section>

<!-- Login Modal -->
@if(Session::has('errorMessage'))
    <div class="modal fade" data-dismiss="modal" id="loginModal" tabindex="-1" data-errors="true" role="dialog"
         aria-labelledby="loginModal" aria-hidden="true">
        @else
            <div class="modal fade" id="loginModal" tabindex="-1" data-errors="false" role="dialog"
                 aria-labelledby="loginModal" aria-hidden="true">
                @endif
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{ucfirst(trans('educal.loginaccount'))}}</h4>
                </div>
                <div class="modal-body">
                    @if(Session::has('errorMessage'))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ucfirst(trans('educal.errors'))}}</strong>
                            <ul>
                                <li>{{ Session::get('errorMessage') }}</li>
                            </ul>
                        </div>
                    @endif
                    {{ Form::open([
                    'route' => 'user.auth',
                    'data-ajax' => 'false',
                    ]), PHP_EOL }}
                    <div class="form-group">
                        {{Form::label('lemail', ucfirst(trans('educal.email')))}}
                        {{Form::email('lemail', null , ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        <label for="login-password">{{ucfirst(trans('educal.password'))}}</label>
                        <input type="password" class="form-control" id="login-password" name="password">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="login-remember"
                                   id="login-remember">{{ ucfirst(trans('educal.remember'))}}
                        </label>
                    </div>
                    <button type="submit"
                            class="btn btn-default btn-educal-danger">{{ ucfirst(trans('educal.login'))}}</button>
                    <a href="#" data-dismiss="modal" class="btn btn-default" data-toggle="modal"
                       data-target="#requestResetPasswordLink">Wachtwoord vergeten</a>
                </div>
                {{ Form::close(), PHP_EOL }}
            </div>

        </div>
    </div>

</body>
