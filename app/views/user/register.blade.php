@extends('layout.form')

@section('header')
    <h1 class="form-header">De eerste stap in de goede richting!</h1>
    <h3 class="form-header">Registreer je school hier. Heb je al een account? <a class="nav-login"
                                                                                 href="{{route('user.login')}}">
            <strong>Log hier in</strong></a></h3>
@stop

@section('form')

    {{ Form::open([
                 'route' => 'school.store',
                 'data-ajax' => 'false',
                 ]), PHP_EOL }}
    <div class="form-group">
        {{Form::label('user-firstname', 'Voornaam')}}
        {{Form::text('user-firstname', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer voornaam in'])}}
    </div>
    <div class="form-group">
        {{Form::label('user-lastname', 'Achternaam')}}
        {{Form::text('user-lastname', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer achternaam in'])}}
    </div>
    <div class="form-group">
        {{Form::label('school-name', 'Naam van school')}}
        {{Form::text('school-name', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer naam van school in'])}}
    </div>
    <div class="form-group">
        {{Form::label('user-email', 'E-mail adres')}}
        {{Form::email('user-email', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer e-mail in'])}}
    </div>
    <div class="form-group">
        {{Form::label('user-password', 'Wachtwoord')}}
        {{Form::password('user-password', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer wachtwoord in'])}}
    </div>
    <div class="form-group">
        {{Form::label('user-password-confirm', 'Herhaal wachtwoord')}}
        {{Form::password('user-password-confirm', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Bevestig wachtwoord'])}}
    </div>
    <div class="form-group">
        {{Form::label('school-city', 'Stad')}}
        {{Form::text('school-city', null , ['class'=>'form-control right', 'required' => true, 'placeholder' => 'Voer locatie van school in'])}}

        @if(Session::has('errorMessage'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('errorMessage') }}
            </div>
        @endif
    </div>
    <button type="submit"
            class="btn btn-info">Start met plannen
    </button>

    {{ Form::close(), PHP_EOL }}

@stop