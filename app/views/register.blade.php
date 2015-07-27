@extends('layout.form')

@section('form')

    {{ Form::open([
                 'route' => 'user.auth',
                 'data-ajax' => 'false',
                 ]), PHP_EOL }}
    <div class="form-group">
        <label for="login-password">Naam van school</label>
        <input type="text" class="form-control right" id="register-name" name="city"
               placeholder="Voer naam in" required>
    </div>
    <div class="form-group">
        <label for="login-mail">E-mail</label>
        <input type="text" class="form-control right" id="register-mail" name="city"
               placeholder="Voer e-mail in" required>
    </div>
    <div class="form-group">
        <label for="login-password">Wachtwoord</label>
        <input type="password" class="form-control right" id="login-password" name="password"
               placeholder="Voer wachtwoord in" required>

    </div>
    <div class="form-group">
        <label for="login-password">{{ucfirst(trans('educal.password'))}}</label>
        <input type="text" class="form-control right" id="login-city" name="city"
               placeholder="Voer stad in" required>
        @if(Session::has('errorMessage'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('errorMessage') }}
            </div>
        @endif
    </div>
    <button type="submit"
            class="btn btn-info btn-fullwidth">Start met plannen
    </button>

    {{ Form::close(), PHP_EOL }}

@stop