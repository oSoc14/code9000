@extends('layout.form')

@section('form')
    <h2 class="form-header">Herstel wachtwoord<span class="form-header-mute"> - via email</span></h2>
    {{Form::open(array('route' => array('user.sendResetLink'), 'class'=>'form form-horizontal', 'method' => 'post')) }}

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
        {{Form::label('email', 'E-mail adres')}}

        {{Form::email('email', '' , ['class'=>'form-control'])}}

    </div>
    <button type="submit"
            class="btn btn-info">Verstuur
    </button>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}

@stop
