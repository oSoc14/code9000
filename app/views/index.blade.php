@extends('layout.master')

@section('header')
{{ HTML::style("css/landing.css") }}
@section('content')

<div class="container">

    {{ Form::open([
        'route' => 'user.auth',
        'data-ajax' => 'false',
    ]), PHP_EOL }}
    <h1>Login</h1>
    <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="remember" id="remember"> Remember me
        </label>
    </div>
    <button type="submit" class="btn btn-primary">Log in</button>
    {{ Form::close(), PHP_EOL }}
    <a href="#">Register for an account</a>

    <div class="footer">
        <p>&copy; OKFN Belgium 2014</p>
    </div>

</div> <!-- /container -->

@stop