@extends('layout.form')

@section('header')
    <h2 class="form-header">Log in<span class="form-header-mute"> - voor medewerkers</span></h2>
@stop

@section('form')

    {{ Form::open([
       'route' => 'user.auth',
       'data-ajax' => 'false',
       ]), PHP_EOL }}
    <div class="form-group">
        {{Form::label('lemail', ucfirst(trans('educal.email')))}}
        {{Form::email('lemail', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer e-mailadres in'])}}
    </div>
    <div class="form-group">
        <label for="login-password">{{ucfirst(trans('educal.password'))}}</label>
        <input type="password" class="form-control right" id="login-password" name="password"
               placeholder="Voer wachtwoord in" required>
        @if(Session::has('errorMessage'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('errorMessage') }}
            </div>
        @endif
    </div>

    <button type="submit"
            class="btn btn-info">{{ ucfirst(trans('educal.login'))}}</button>
    <a href="#" data-dismiss="modal" data-toggle="modal"
       data-target="#requestResetPasswordLink">Wachtwoord vergeten</a>
    </div>
    {{ Form::close(), PHP_EOL }}

@stop
