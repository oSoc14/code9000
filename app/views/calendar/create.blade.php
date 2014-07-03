@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

        @section('content')
        <!-- main area -->
        <div class="col-xs-12 col-sm-9">
            {{ Form::open([
            'route' => 'event.store',
            'data-ajax' => 'false',
            ]), PHP_EOL }}
            <h1>Create Event</h1>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="tex" class="form-control" id="title" name="title" placeholder="Event title">
            </div>
            <div class="form-group">
                <label for="group">Group</label>
                {{Form::select('group', $groups, [], array('class'=>'form-control'));}}
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea  class="form-control" rows="3" name="description" id="description"></textarea>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' id='datetimepicker1' name="start" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' id='datetimepicker2' name="end" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}

        </div><!-- /.col-xs-12 main -->
    </div><!--/.row-->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop