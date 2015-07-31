@extends('layout.form')

@section('header')

@stop

@section('form')
    <h2 class="form-header">Log in<span class="form-header-mute"> - voor medewerkers</span></h2>
    {{ Form::open([
       'route' => 'user.auth',
       'data-ajax' => 'false',
       ]), PHP_EOL }}
    <div class="form-group">
        {{Form::label('lemail', ucfirst(trans('educal.email')))}}
        {{Form::email('lemail', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'jouw@email.com'])}}
    </div>
    <div class="form-group">
        <label for="login-password">{{ucfirst(trans('educal.password'))}}</label>
        <input type="password" class="form-control right" id="login-password" name="password"
               required>
        @if(Session::has('errorMessage'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('errorMessage') }}
            </div>
        @endif
    </div>

    <button type="submit"
            class="btn btn-info">{{ ucfirst(trans('educal.login'))}}</button>
    <a href="{{ route( 'user.requestResetMail') }}">Wachtwoord vergeten</a>
    </div>
    {{ Form::close(), PHP_EOL }}

@stop
