@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
  <div class="col-xs-12 col-sm-9">
    <a href="{{ route('calendar.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> Back to calendar</a>
    <h1>Edit Event</h1>
      {{Form::open(array('route' => array('event.update',$event->id), 'class'=>'form-horizontal')) }}

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
          {{Form::label('title', 'Title', array('class'=>'col-sm-12 col-md-2 control-label')) }}
        <div class="col-sm-12 col-md-10">
          {{Form::text('title', $event->title , ['class'=>'form-control','placeholder'=>"What's the title of your event?"])}}
        </div>
      </div>
      <div class="form-group">
          {{Form::label('group', 'Group', array('class'=>'col-sm-12 col-md-2 control-label'))}}
        <div class="col-sm-12 col-md-10">
          {{Form::select('group', $groups, $event->group_id, array('class'=>'form-control'));}}
        </div>
      </div>
      <div class="form-group">
          {{Form::label('description', 'Description', array('class'=>'col-sm-12 col-md-2 control-label'))}}
        <div class="col-sm-12 col-md-10">
          {{Form::textarea('description', $event->description , ['class'=>'form-control','placeholder'=>"Enter some details about the event", 'rows'=>3])}}
        </div>
      </div>

      <div class="form-group">
        {{Form::label('datetimepicker1', 'Startdate', array('class'=>'col-sm-12 col-md-2 control-label'))}}
        <div class="col-sm-12 col-md-10">
          <div class='input-group date'>
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            {{Form::text('start', date_format(new DateTime($event->start_date), 'Y/m/d H:i') , ['class'=>'form-control','id'=>'datetimepicker1'])}}
          </div>
        </div>
      </div>
      <div class="form-group">
          {{Form::label('datetimepicker2', 'Enddate', array('class'=>'col-sm-12 col-md-2 control-label'))}}
          <div class="col-sm-12 col-md-10">
          <div class='input-group date'>
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              {{Form::text('end', date_format(new DateTime($event->end_date), 'Y/m/d H:i') , ['class'=>'form-control','id'=>'datetimepicker2'])}}
          </div>
          </div>
      </div>

      <div class="form-group">
        <div class="col-md-offset-2 col-sm-12 col-md-5">
          <div class="checkbox">
              <label>
                  <input name="day" type="checkbox" {{ ($event->allday?'checked':'')}}> Full day
              </label>
          </div>
        </div>
        <div class="col-md-offset-2 col-sm-12 col-md-5">
          <div class="checkbox">
              <label>
                  <input type="checkbox" name="repeat" id="repeat" {{ ($event->nr_repeat?'checked':'')}}> Repeating event
              </label>
          </div>
        </div>
      </div>
    <div class="form-repeat-container">
      <div class="form-group">
        <label for="repeat_freq" class="col-xs-12 col-sm-12 col-md-2 control-label">Every...</label>
        <div class="col-xs-6 col-md-3">
          <div class="input-group">
              <input type="number" id="repeat_freq" name="repeat_freq" class="form-control" value="{{ $event->repeat_freq }}"/>
              <span class="input-group-addon"><span class="glyphicon glyphicon-cog"></span></span>
          </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3">
            {{Form::select('repeat_type', ['d'=>'Days','w'=>'Weeks','M'=>'Months','y'=>'Years' ], $event->repeat_type, array('class'=>'form-control', 'id'=>'repeat_type'))}}
        </div>
      </div>

      <div class="form-group">
        <label for="datetimepicker3" class="col-sm-12 col-md-2 control-label">Until...</label>
        <div class="col-sm-12 col-md-6">
            <div class='input-group date'>
              {{Form::text('recurrence_end', null , ['class'=>'form-control','id'=>'datetimepicker3'])}}
                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
      </div>
    </div>

    <input type="hidden" id="nr_repeat" name="nr_repeat" value="{{ $event->nr_repeat }}"/>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-educal-primary"><i class="fa fa-save"></i> Save changes</button>
        <a type="button" class="btn btn-default btn-educal-danger" href="{{route('event.delete',$event->id)}}" id="deleteEvent">
            <i class="fa fa-times-circle"></i> Delete Event
        </a>
      </div>
    </div>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}

  </div><!-- /.col-xs-12 main -->
<!-- TODO: Frequency, interval, count velden -->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop