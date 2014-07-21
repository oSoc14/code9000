@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')

<div class="col-xs-12 col-sm-9">
    <a href="{{ route('school.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.school')]))}}</a>
    {{Form::open(array('route' => array('school.update',$school->id), 'class'=>'form-horizontal')) }}
    <h1> {{ucfirst(trans('educal.editschool'))}}</h1>

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
        {{Form::label('name', ucfirst(trans('educal.name')))}}
        {{Form::text('name', $school->name , ['class'=>'form-control','placeholder'=>"What'the name of the school?"])}}
    </div>
    <div class="form-group">
        {{Form::label('city', ucfirst(trans('educal.city')))}}
        {{Form::text('city', $school->city , ['class'=>'form-control','placeholder'=>"Where is the school located? (e.g. 'Chicago')"])}}
    </div>
    <button type="submit" class="btn btn-default btn-educal-danger">{{ucfirst(trans('educal.savechanges'))}}</button>

    {{ Form::close(), PHP_EOL }}

</div><!-- /.col-xs-12 main -->
<!-- TODO: Frequency, interval, count velden -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop