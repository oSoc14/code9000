<!doctype html>
<html lang="{{ Config::get('app.locale') }}" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ HTML::entities('EduCal') }}</title>
  <!-- Bootstrap core CSS -->
  {{ HTML::style("css/bootstrap.min.css") }}
  {{ HTML::style("css/bootstrap-theme.min.css") }}
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
  <meta name="msapplication-TileColor" content="#39acb0">
  <meta name="msapplication-TileImage" content="favicons/mstile-144x144.png">
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
        <p class="lead">EduCal is an application for schools to easily create and manage calendars that can be shared with parents.</p>
        <div class="button-container">
          <a href="#" class="btn btn-lg btn-default btn-educal-secondary" data-toggle="modal" data-target="#loginModal">Log in <span class="glyphicon glyphicon-log-in"></span></a> or
          <a href="#" class="btn btn-lg btn-default btn-educal-primary" data-toggle="modal" data-target="#registerUserModal">Register <span class="glyphicon glyphicon-link"></span></a>
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
      Created by team Code9000 during <a href="http://summerofcode.be">open Summer of Code 2014</a>.
    </footer>
  </div>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Log in to your account</h4>
        </div>
        <div class="modal-body">
          {{ Form::open([
          'route' => 'user.auth',
          'data-ajax' => 'false',
          ]), PHP_EOL }}
          <div class="form-group">
            <label for="login-email">Email address</label>
            <input type="email" class="form-control" id="login-email" name="email" placeholder="What's your email address?">
          </div>
          <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" class="form-control" id="login-password" name="password" placeholder="Enter your password">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="login-remember" id="login-remember"> Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-educal-primary">Log in</button>
          {{ Form::close(), PHP_EOL }}
          {{ Session::get('errorMessage') }}
        </div>
      </div>
    </div>
  </div>

  <!-- Register (user) Modal -->
  @if($errors->has())
  <div class="modal fade" id="registerUserModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="registerUserModal" aria-hidden="false">
  @else
  <div class="modal fade" id="registerUserModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="registerUserModal" aria-hidden="false">
  @endif
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Register as a user</h4>
        </div>
        <div class="modal-body">
            @foreach ($errors->all() as $message)
            {{$message}}
            @endforeach

            {{ Form::open([
            'route' => 'user.register',
            'data-ajax' => 'true',
            ]), PHP_EOL }}
          <div class="alert alert-warning" role="alert">Warning! If you want to register as a school, click <a href="#" id="showSchoolRegisterModal">here</a>.</div>
          <div class="form-group">
              <label for="user-email">Name</label>
              <input type="text" class="form-control" id="user-name" name="name" placeholder="What's your given name?">
          </div>
          <div class="form-group">
              <label for="user-email">Surname</label>
              <input type="text" class="form-control" id="user-surname" name="surname" placeholder="What's your surname?">
          </div>
          <div class="form-group">
            <label for="user-email">Email address</label>
            <input type="email" class="form-control" id="user-email" name="email" placeholder="What's your email address?">
          </div>
          <div class="form-group">
            <label for="user-password">Password</label>
            <input type="password" class="form-control" id="user-password" name="password" placeholder="Choose a password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="user-password-confirmation" name="password_confirmation" placeholder="Repeat that password here">
          </div>
          <div class="form-group">
            <label>School</label>
            {{ Form::select('school', $schools, null, array('class' => 'form-control')) }}
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="tos" id="tos"> I agree to the <a href="#">terms of service</a>.
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-educal-primary">Register</button>
            {{ Form::close(), PHP_EOL }}
        </div>
      </div>
    </div>
  </div>

  <!-- Register (school) Modal -->
  @if($errors->has())
  <div class="modal fade" id="registerSchoolModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="registerSchoolModal" aria-hidden="false">
  @else
  <div class="modal fade" id="registerSchoolModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="registerSchoolModal" aria-hidden="false">
  @endif
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Register as a school</h4>
        </div>
        <div class="modal-body">
            @foreach ($errors->all() as $message)
            {{$message}}
            @endforeach

            {{ Form::open([
            'route' => 'school.store',
            'data-ajax' => 'true',
            ]), PHP_EOL }}
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="school-email" name="email" placeholder="What's your school's email address?">
          </div>
          <div class="form-group">
            <label for="school-name">Name</label>
            <input type="text" class="form-control" id="school-name" name="name" placeholder="What'the name of the school?">
          </div>
          <div class="form-group">
            <label for="school-location">City</label>
            <input type="text" class="form-control" id="school-location" name="school-location" placeholder="Where is the school located? (e.g. 'Chicago')">
          </div>
          <div class="form-group">
            <label for="school-password">Password</label>
            <input type="password" class="form-control" id="school-password" name="password" placeholder="Choose a password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="school-password-confirmation" name="password_confirmation" placeholder="Repeat that password here">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="tos" id="tos"> I agree to the <a href="#">terms of service</a>
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-educal-primary">Register</button>
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