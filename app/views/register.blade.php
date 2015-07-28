@extends('layout.form')

@section('header')
    <h1 class="form-header">De eerste stap in de goede richting!</h1>
    <h3 class="form-header">Registreer je school hier. Heb je al een account? <a class="nav-login" href="#login"
                                                                                 data-toggle="modal"
                                                                                 data-target="#loginmodal"><strong>Log
                hier in</strong></a></h3>
@stop

@section('form')

    {{ Form::open([
                 'route' => 'school.store',
                 'data-ajax' => 'false',
                 ]), PHP_EOL }}
    <div class="form-group">
        <label for="user-firstname">Voornaam</label>
        <input type="text" class="form-control right" id="register-name" name="user-firstname"
               placeholder="Voer voornaam in" required>
    </div>
    <div class="form-group">
        <label for=user-lastname">Achternaam</label>
        <input type="text" class="form-control right" id="register-name" name="user-lastname"
               placeholder="Voer achternaam in" required>
    </div>
    <div class="form-group">
        <label for="school-name">Naam van school</label>
        <input type="text" class="form-control right" id="register-name" name="school-name"
               placeholder="Voer naam van school in" required>
    </div>
    <div class="form-group">
        <label for="user-email">E-mail</label>
        <input type="email" class="form-control right" id="register-mail" name="user-email"
               placeholder="Voer e-mail in" required>
    </div>
    <div class="form-group">
        <label for="user-password">Wachtwoord</label>
        <input type="password" class="form-control right" id="user-password" name="user-password"
               placeholder="Voer wachtwoord in" required>

    </div>
    <div class="form-group">
        <label for="user-password-confirm">Herhaal wachtwoord</label>
        <input type="password" class="form-control right" id="user-password-confirm" name="user-password-confirm"
               placeholder="Herhaal wachtwoord" required>

    </div>
    <div class="form-group">
        <label for="school-city">Stad</label>
        <input type="text" class="form-control right" id="school-city" name="school-city"
               placeholder="Voer stad in" required>
    </div>
    <button type="submit"
            class="btn btn-info">Start met plannen
    </button>

    {{ Form::close(), PHP_EOL }}

@stop