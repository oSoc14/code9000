@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<!-- main area -->
<div class="col-xs-12 col-sm-9">
    {{Form::open(array('route' => array('user.update',$user->id)))}}
    <h1>Edit information</h1>
    @if($errors->count())
    <div class="alert alert-danger" role="alert">
        <strong>Errors</strong>
        <ul>
            @foreach ($errors->all() as $message)
            <li>{{$message}}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="form-group">
        {{Form::label('name', 'First name')}}
        {{Form::text('name', $user->first_name , ['class'=>'form-control', 'placeholder'=>$user->first_name])}}
    </div>
    <div class="form-group">
        {{Form::label('surname', 'Last name')}}
        {{Form::text('surname', $user->last_name , ['class'=>'form-control', 'placeholder'=>$user->last_name])}}
    </div>
    <div class="form-group">
        {{Form::label('email', 'E-mail')}}
        {{Form::email('email', $user->email , ['class'=>'form-control', 'placeholder'=>$user->email])}}
    </div>
    <div class="form-group">
        <label>Language</label>
        {{ Form::select('lang', ['nl' => 'nl','fr' => 'fr','en' => 'en','de' => 'de'], Session::get('lang'), array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="New password">
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat new password">
    </div>
    <button type="submit" class="btn btn-primary">Edit information</button>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}

</div><!-- /.col-xs-12 main -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop