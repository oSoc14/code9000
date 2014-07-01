@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

<div class="page-container">

    @extends('layout.navbar')

    <div class="container">
        @extends('layout.sidebar')

        @section('content')
        <!-- main area -->
        <div class="col-xs-12 col-sm-9">
            {{ Form::open([
            'route' => 'user.auth',
            'data-ajax' => 'false',
            ]), PHP_EOL }}
            <h1>Create Event</h1>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="tex" class="form-control" id="title" name="title" placeholder="Event title">
            </div>
            <div class="form-group">
                <label for="group">Group</label>
                {{Form::select('group', $groups);}}
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
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