@extends('layout.master')

@section('header')
{{ HTML::style("css/landing.css") }}
@section('content')

<div class="container">

    <form role="form">
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
                <input type="checkbox"> I agree bla bla
            </label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>

    <div class="footer">
        <p>&copy; OKFN Belgium 2014</p>
    </div>

</div> <!-- /container -->

@stop