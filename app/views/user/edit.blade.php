@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <a href="{{ route('user.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.users')]))}}</a>
      <h1>{{ucfirst(trans('educal.editinformation'))}}</h1>

      {{Form::open(array('route' => array('user.update',$user->id), 'class'=>'form form-horizontal')) }}

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
        {{Form::label('name', ucfirst(trans('educal.name')), array('class'=>'col-md-2 control-label'))}}
      <div class="col-md-8">
        {{Form::text('name', $user->first_name , ['class'=>'form-control', 'placeholder'=>$user->first_name])}}
      </div>
    </div>
    <div class="form-group">
        {{Form::label('surname', ucfirst(trans('educal.surname')), array('class'=>'col-md-2 control-label'))}}
      <div class="col-md-8">
        {{Form::text('surname', $user->last_name , ['class'=>'form-control', 'placeholder'=>$user->last_name])}}
      </div>
    </div>
    <div class="form-group">
        {{Form::label('email', ucfirst(trans('educal.email')), array('class'=>'col-md-2 control-label'))}}
      <div class="col-md-8">
        {{Form::email('email', $user->email , ['class'=>'form-control', 'placeholder'=>$user->email])}}
      </div>
    </div>
    <div class="form-group">
        {{Form::label('lang', ucfirst(trans('educal.language')), array('class'=>'col-md-2 control-label'))}}
        <div class="col-md-8">
            {{ Form::select('lang', ['nl' => 'Nederlands','fr' => 'Français','en' => 'English','de' => 'Deutsch'], Session::get('lang'), array('class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
      <label for="password" class="col-md-2 control-label">{{ucfirst(trans('educal.newpassword'))}}</label>
      <div class="col-md-8">
        <input type="password" class="form-control" id="password" name="password">
      </div>
    </div>
    <div class="form-group">
        <label for="password_confirmation" class="col-md-2 control-label">{{ucfirst(trans('educal.repeatpassword'))}}</label>
      <div class="col-md-8">
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-offset-2 col-md-8">
        <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-save"></i> {{ucfirst(trans('educal.savechanges'))}}</button>
      </div>
    </div>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}

  </div>
  </div>
</div>

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop