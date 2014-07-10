@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

        @section('content')
        <!-- main area -->
<div class="col-xs-12">
  <ol class="breadcrumb">
    <li><a href="../">Home</a></li>
    <li><a href="{{ route('calendar.index') }}">Calendar</a></li>
    <li class="active">Create event</li>
  </ol>
</div>
        <div class="col-xs-12 col-sm-9">
            {{ Form::open([
            'route' => 'event.store',
            'data-ajax' => 'false',
            ]), PHP_EOL }}
            <h1>Create Event</h1>

            @foreach ($errors->all() as $message)
            {{$message}}
            @endforeach

            <div class="form-group">
                {{Form::label('title', 'Title')}}
                {{Form::text('title', null , ['class'=>'form-control','placeholder'=>"Event title"])}}
            </div>
            <div class="form-group">
                {{Form::label('group', 'Group')}}
                {{Form::select('group', $groups, [], array('class'=>'form-control'))}}
            </div>
            <div class="form-group">
                {{Form::label('description', 'Description')}}
                {{Form::textarea('description', null , ['class'=>'form-control','placeholder'=>"Event description", 'rows'=>3])}}
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    {{Form::text('start', null , ['class'=>'form-control','id'=>'datetimepicker1'])}}
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    {{Form::text('end', null , ['class'=>'form-control','id'=>'datetimepicker2'])}}
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </div>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="day" id="day"> Full day
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="repeat" id="repeat"> Repeating event
                </label>
            </div>
            <label>Recurrence (every x period until date)</label>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="number" id="repeat_freq" name="repeat_freq" class="form-control" min="1" value="1"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-cog"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {{Form::select('repeat_type', ['d'=>'Day','w'=>'Week','M'=>'Month','y'=>'Year' ], [], array('class'=>'form-control'))}}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class='input-group date'>
                            {{Form::text('recurrence_end', null , ['class'=>'form-control','id'=>'datetimepicker3'])}}
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="nr_repeat" name="nr_repeat" />
            <button type="submit" class="btn btn-default btn-educal-danger">Add Event</button>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}
            <!-- TODO: Toggle visibility of repeat values -->
        </div><!-- /.col-xs-12 main -->
    </div><!--/.row-->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop