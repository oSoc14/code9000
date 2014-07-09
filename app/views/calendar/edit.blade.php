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
                <label for="title">Title</label>
                <input type="tex" class="form-control" id="title" name="title" value="{{$event->title}}" placeholder="{{$event->title}}">
            </div>
            <div class="form-group">
                <label for="group">Group</label>
                {{Form::select('group', $groups, $event->group_id, array('class'=>'form-control'));}}
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{$event->description}}</textarea>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' id='datetimepicker1' value="{{date_format(new DateTime($event->start_date), 'Y/m/d H:i')}}" name="start" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' id='datetimepicker2' value="{{date_format(new DateTime($event->end_date), 'Y/m/d H:i')}}" name="end" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input name="day" type="checkbox"> All day
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
                        <select id="repeat_type" name="repeat_type" class="form-control">
                            <option value="d" {{ ($event->repeat_type=='d'?'selected':'') }}>Day</option>
                            <option value="w" {{ ($event->repeat_type=='w'?'selected':'') }}>Week</option>
                            <option value="M" {{ ($event->repeat_type=='M'?'selected':'') }}>Month</option>
                            <option value="y" {{ ($event->repeat_type=='y'?'selected':'') }}>Year</option>
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
            <input type="hidden" id="nr_repeat" name="nr_repeat" value="{{ $event->nr_repeat }}"/>
            <button type="submit" class="btn btn-primary">Edit Event</button>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}

        </div><!-- /.col-xs-12 main -->
<!-- TODO: Frequency, interval, count velden -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop