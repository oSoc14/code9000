@extends('layout.master')

@section('header')
{{ HTML::style("css/landing.css") }}
@section('content')

<div class="container">

  <form role="form">
    <h1>Login</h1>
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary">Log in</button>
  </form>
  <a href="#">Register for an account</a>

  <div class="footer">
    <p>&copy; OKFN Belgium 2014</p>
  </div>

</div> <!-- /container -->

@stop