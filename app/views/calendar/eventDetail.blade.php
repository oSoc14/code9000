@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<!-- main area -->
<div class="col-xs-12 col-sm-9">
    <h1>{{$event->title}}</h1>
    <span>Group: {{$event->group->name}}</span>
    <br>
    <div class="form-group">
        <label for="description">Description</label>
        <p>{{$event->description}}</p>
    </div>
    <div class="form-group">
        <div class='input-group date'>
            <p>{{date_format(new DateTime($event->start_date), 'Y/m/d H:i')}}</p>

        </div>
    </div>
    <div class="form-group">
        <div class='input-group date'>
            <p>{{date_format(new DateTime($event->end_date), 'Y/m/d H:i')}}</p>

        </div>
    </div>
    <!-- TODO: check if user has rights for edit, then display button -->
    <a type="button" class="btn btn-default btn-lg" href="{{route('event.edit',$event->id)}}" id="editEvent">
        <span class="glyphicon glyphicon-pencil"></span> Edit Event
    </a>
</div><!-- /.col-xs-12 main -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop