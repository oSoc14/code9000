<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ HTML::entities('EduCal') }}</title>
  <!-- Bootstrap core CSS -->
  {{ HTML::style("css/bootstrap.min.css") }}
  {{ HTML::style("css/bootstrap-theme.min.css") }}
  <!-- FontAwesome icons -->
  {{ HTML::style("//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css") }}
  <!-- Google Webfont -->
  {{ HTML::style("http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700") }}
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
<body>

<div class="page-container">

  <div class="container-fluid">
    <div class="row introduction-container">
      <div class="col-xs-12 introduction-content">
        <h1 class="hidden">EduCal</h1>
        <img src="images/logo_educal.png" alt="Logo" />
        <p class="lead">{{trans('educal.lead')}}</p>
        <div class="button-container">
          <a href="#" class="btn btn-lg btn-default btn-educal-warning" data-toggle="modal" data-target="#loginModal">{{ucfirst(trans('educal.login'))}} <i class="fa fa-sign-in"></i></a> {{trans('educal.or')}}
          <a href="#" class="btn btn-lg btn-default btn-educal-danger" data-toggle="modal" data-target="#registerUserModal">{{ucfirst(trans('educal.register'))}} <i class="fa fa-pencil-square-o"></i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="container content-container">
    <div class="row content-container">
      <div class="col-xs-12 col-md-4">
        <h2>{{ucfirst(trans('educal.about'))}}</h2>
        <p>Nowadays, parents have a lot of different calendars to keep track of for their children. <strong>EduCal</strong> is an interactive calendar management platform that allows schools (and others) to manage multiple calendars. These calendars can be downloaded by staff and parents as an iCal-file or pdf-file. This iCal-file can then be <strong>imported</strong> in their own agendas.</p>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <h2>{{ucfirst(trans('educal.getstarted'))}}</h2>
        <p>To get started with EduCal, you first need to make an account. You can do this as either a <strong>user</strong> or a <strong>school</strong>. As soon as you're registered, the account needs to be <strong>activated</strong> by the administrator of the school you applied to. This does not happen automatically. After that, the admin will also place you in your correct <strong>groups</strong> and you are ready to go!</p>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <h2>{{ucfirst(trans('educal.contact'))}}</h2>
        <p>This platform is created by the Code9000 team as part of <a href="http://summerofcode.be">open Summer of Code 2014</a> by
          <a href="http://www.okfn.be">OKFN Belgium</a></p>
        <p>EduCal is sponsored by <a href="http://www.digipolis.be">Digipolis</a> and the <a href="http://www.gent.be">city of Ghent</a>.</p>
        <p>&copy; OKFN Belgium</p>
      </div>
    </div>
  </div>

  <!-- Login Modal -->
  @if(Session::has('errorMessage'))
  <div class="modal fade" id="loginModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  @else
  <div class="modal fade" id="loginModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  @endif
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
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
          {{Form::email('lemail', null , ['class'=>'form-control','placeholder'=>"What's your email address?"])}}
          </div>
          <div class="form-group">
            <label for="login-password">{{ucfirst(trans('educal.password'))}}</label>
            <input type="password" class="form-control" id="login-password" name="password" placeholder="Enter your password">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="login-remember" id="login-remember">{{ ucfirst(trans('educal.remember'))}}
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-educal-danger">{{ ucfirst(trans('educal.login'))}}</button>
          {{ Form::close(), PHP_EOL }}
        </div>
      </div>
    </div>
  </div>

  <!-- Register (user) Modal -->
  @if($errors->has('usererror'))
  <div class="modal fade" id="registerUserModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="registerUserModal" aria-hidden="false">
  @else
  <div class="modal fade" id="registerUserModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="registerUserModal" aria-hidden="false">
  @endif
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">{{ ucfirst(trans('educal.registeruser'))}}</h4>
        </div>
        <div class="modal-body">
            @if($errors->has('usererror'))
                <div class="alert alert-danger" role="alert">
                  <strong>{{ucfirst(trans('educal.errors'))}}</strong>
                  <ul>
                @foreach ($errors->all() as $message)
                    <li>{{$message}}</li>
                @endforeach
                  </ul>
                </div>
            @endif
            {{ Form::open([
            'route' => 'user.register',
            'data-ajax' => 'true',
            ]), PHP_EOL }}
          <div class="alert alert-warning" role="alert">{{ucfirst(trans('educal.registerschool'))}}</div>
          <div class="form-group">
              {{Form::label('name', ucfirst(trans('educal.name')))}}
              {{Form::text('name', null , ['class'=>'form-control','placeholder'=>"What's your given name?"])}}
          </div>
          <div class="form-group">
              {{Form::label('surname', ucfirst(trans('educal.surname')))}}
              {{Form::text('surname', null , ['class'=>'form-control','placeholder'=>"What's your surname?"])}}
          </div>
          <div class="form-group">
              {{Form::label('email', ucfirst(trans('educal.email')))}}
              {{Form::email('email', null , ['class'=>'form-control','placeholder'=>"What's your email address?"])}}
          </div>
          <div class="form-group">
            <label for="user-password">{{ucfirst(trans('educal.password'))}}</label>
            <input type="password" class="form-control" id="user-password" name="password" placeholder="Choose a password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="user-password-confirmation" name="password_confirmation" placeholder="Repeat that password here">
          </div>
          <div class="form-group">
            <label>{{ucfirst(trans('educal.school'))}}</label>
            {{ Form::select('school', $schools, null, array('class' => 'form-control')) }}
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="tos" id="tos">{{ucfirst(trans('educal.terms'))}}
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-educal-danger">{{ucfirst(trans('educal.register'))}}</button>
            {{ Form::close(), PHP_EOL }}
        </div>
      </div>
    </div>
  </div>

  <!-- Register (school) Modal -->
  @if($errors->has('schoolerror'))

  <div class="modal fade" id="registerSchoolModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="registerSchoolModal" aria-hidden="false">
  @else
  <div class="modal fade" id="registerSchoolModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="registerSchoolModal" aria-hidden="false">
  @endif
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">{{ucfirst(trans('educal.registeraschool'))}}</h4>
        </div>
        <div class="modal-body">
            @if($errors->has('schoolerror'))
              <div class="alert alert-danger" role="alert">
                <strong>{{ucfirst(trans('educal.errors'))}}</strong>
                <ul>
                  @foreach ($errors->all() as $message)
                  <li>{{$message}}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            {{ Form::open([
            'route' => 'school.store',
            'data-ajax' => 'true',
            ]), PHP_EOL }}
          <div class="form-group">
              {{Form::label('semail', ucfirst(trans('educal.email')))}}
              {{Form::email('semail', null , ['class'=>'form-control','placeholder'=>"What's your school's email address?"])}}
          </div>
          <div class="form-group">
              {{Form::label('sname', ucfirst(trans('educal.name')))}}
              {{Form::text('sname', null , ['class'=>'form-control','placeholder'=>"What's the name of the school?"])}}

          </div>
          <div class="form-group">
              {{Form::label('city', ucfirst(trans('educal.city')))}}
              {{Form::text('city', null , ['class'=>'form-control','placeholder'=>"Where is the school located? (e.g. 'Chicago')"])}}
          </div>
          <div class="form-group">
            <label for="school-password">{{ucfirst(trans('educal.password'))}}</label>
            <input type="password" class="form-control" id="school-password" name="password" placeholder="Choose a password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="school-password-confirmation" name="password_confirmation" placeholder="Repeat that password here">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="tos" id="tos">{{ucfirst(trans('educal.terms'))}}
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-educal-danger">{{ucfirst(trans('educal.register'))}}</button>
            {{ Form::close(), PHP_EOL }}
        </div>
      </div>
    </div>
  </div>

</div>

{{ HTML::script("js/jquery-1.11.1.min.js") }}
{{ HTML::script("js/bootstrap.min.js") }}
{{ HTML::script("js/app.js") }}

</body>