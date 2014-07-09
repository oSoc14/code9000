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
                            <input type="number" id="repeat_freq" name="repeat_freq" class="form-control" value="1"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-cog"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select id="repeat_type" name="repeat_type" class="form-control">
                            <option value="d">Day</option>
                            <option value="w">Week</option>
                            <option value="M">Month</option>
                            <option value="y">Year</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class='input-group date'>
                            <input type='text' id='datetimepicker3' name="recurrence_end" class="form-control" />
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="nr_repeat" name="nr_repeat" />
            <button type="submit" class="btn btn-default btn-educal-primary">Add Event</button>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}
            <!-- TODO: Frequency, interval, count velden -->

        </div><!-- /.col-xs-12 main -->
    </div><!--/.row-->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop