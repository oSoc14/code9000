<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{ HTML::entities('EduCal') }}</title>
    <!-- Bootstrap core CSS -->
    {{ HTML::style("//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css") }}
    {{ HTML::style("//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css") }}
    <!-- Bootstrap Datepicker -->
    {{ HTML::style("css/datepicker.css") }}
    <!-- Global styles for this template -->
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
@yield('content')
{{ HTML::script("js/jquery-1.11.1.min.js") }}
{{ HTML::script("//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js") }}
{{ HTML::script("js/bootstrap-datepicker.js") }}
</body>
</html>