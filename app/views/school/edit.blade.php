@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-9">
        <a href="{{ route('school.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> Back to schools</a>
        {{Form::open(array('route' => array('school.update',$school->id), 'class'=>'form-horizontal')) }}
        <h1>Edit School</h1>

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
            {{ Form::label('name', 'Name', ['class'=>'col-md-2 control-label']) }}
          <div class="col-md-8">
            {{ Form::text('name', $school->name , ['class'=>'form-control','placeholder'=>"What's the name of the school?"]) }}
          </div>
        </div>
        <div class="form-group">
            {{ Form::label('city', 'City', ['class'=>'col-md-2 control-label']) }}
          <div class="col-md-8">
            {{ Form::text('city', $school->city , ['class'=>'form-control','placeholder'=>"Where is the school located? (e.g. 'Chicago')"])}}
            </div>
        </div>
        <button type="submit" class="btn btn-default btn-educal-danger"><i class="fa fa-save"></i> Save changes</button>

        {{ Form::close(), PHP_EOL }}

    </div>
  </div>
</div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop