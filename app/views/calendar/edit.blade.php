@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

<div class="page-container">


    <div class="container">

        @section('content')
        <!-- main area -->
        <div class="col-xs-12 col-sm-9">
            {{Form::open(array('route' => array('event.update',$event->id)))}}

            <h1>Create Event</h1>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="tex" class="form-control" id="title" name="title" value="{{$event->title}}" placeholder="{{$event->title}}">
            </div>
            <div class="form-group">
                <label for="group">Group</label>
                {{Form::select('group', $groups, $event->group_id);}}
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10">{{$event->description}}</textarea>
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' value="{{$event->start_date}}" name="start" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' value="{{$event->end_date}}" name="end" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Edit Event</button>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}

        </div><!-- /.col-xs-12 main -->
    </div><!--/.row-->
</div><!--/.container-->
</div><!--/.page-container-->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop