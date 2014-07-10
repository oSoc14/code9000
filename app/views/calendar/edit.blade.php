@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

        @section('content')
        <!-- main area -->
        <div class="col-xs-12 col-sm-9">
            {{Form::open(array('route' => array('event.update',$event->id)))}}
            <h1>Edit Event</h1>

            @foreach ($errors->all() as $message)
            {{$message}}
            @endforeach

            <div class="form-group">
                {{Form::label('title', 'Title')}}
                {{Form::text('title', $event->title , ['class'=>'form-control','placeholder'=>"Event title"])}}
            </div>
            <div class="form-group">
                {{Form::label('group', 'Group')}}
                {{Form::select('group', $groups, $event->group_id, array('class'=>'form-control'));}}
            </div>
            <div class="form-group">
                {{Form::label('description', 'Description')}}
                {{Form::textarea('description', $event->description , ['class'=>'form-control','placeholder'=>"Event description", 'rows'=>3])}}
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    {{Form::text('start', date_format(new DateTime($event->start_date), 'Y/m/d H:i') , ['class'=>'form-control','id'=>'datetimepicker1'])}}
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    {{Form::text('end', date_format(new DateTime($event->end_date), 'Y/m/d H:i') , ['class'=>'form-control','id'=>'datetimepicker2'])}}
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input name="day" type="checkbox" {{ ($event->allday?'checked':'')}}> All day
                    </label>
                </div>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="repeat" id="repeat" {{ ($event->nr_repeat?'checked':'')}}> Repeating event
                </label>
            </div>
            <label>Recurrence (every x period until date)</label>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="number" id="repeat_freq" name="repeat_freq" class="form-control" value="{{ $event->repeat_freq }}"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-cog"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                            {{Form::select('repeat_type', ['d'=>'Day','w'=>'Week','M'=>'Month','y'=>'Year' ], $event->repeat_type, array('class'=>'form-control'))}}
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
            <input type="hidden" id="nr_repeat" name="nr_repeat" value="{{ $event->nr_repeat }}"/>
            <button type="submit" class="btn btn-educal-primary">Edit Event</button>
            <a type="button" class="btn btn-default btn-educal-primary" href="{{route('event.delete',$event->id)}}" id="deleteEvent">
                <span class="glyphicon glyphicon-pencil"></span> Delete Event
            </a>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}

        </div><!-- /.col-xs-12 main -->
<!-- TODO: Frequency, interval, count velden -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop