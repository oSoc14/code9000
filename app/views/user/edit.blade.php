@extends('layout.master-app')
@section('header')
    {{ HTML::style("css/admin.css") }}
@stop
@section('content')
    <h1>Pas u persoonlijke informatie aan</h1>

    {{Form::open(array('route' => array('user.update',$user->id), 'class'=>'form form-horizontal educal-form')) }}

    @if($errors->count())
        <div class="alert alert-danger" role="alert">
            <strong>{{ucfirst(trans('educal.errors'))}}</strong>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{$message}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group">
        {{Form::label('name',"Voornaam", array('class'=>'col-md-2 control-label'))}}
        {{Form::text('name', $user->first_name , ['class'=>'form-control', 'placeholder'=>$user->first_name])}}

    </div>
    <div class="form-group">
        {{Form::label('surname', "Familienaam", array('class'=>'col-md-2 control-label'))}}

        {{Form::text('surname', $user->last_name , ['class'=>'form-control', 'placeholder'=>$user->last_name])}}

    </div>
    <div class="form-group">
        {{Form::label('email', "E-mailadres", array('class'=>'col-md-2 control-label'))}}

        {{Form::email('email', $user->email , ['class'=>'form-control', 'placeholder'=>$user->email, 'autocomplete' => 'off'])}}

    </div>

    <div class="form-group">
        <label for="password" class="col-md-2 control-label">Nieuw wachtwoord</label>

        <input type="password" class="form-control" id="password" name="password">

    </div>
    <div class="form-group">
        <label for="password_confirmation"
               class="col-md-2 control-label">Herhaal wachtwoord</label>

        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">

    </div>
    <button type="submit"
            class="btn btn-primary">Opslaan
    </button>

    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}

@stop
