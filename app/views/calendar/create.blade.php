@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

        @section('content')
        <!-- main area -->
<div class="col-xs-12">
  <ol class="breadcrumb">
    <li><a href="../">Home</a></li>
    <li><a href="{{ route('calendar.index') }}">{{ucfirst(trans('educal.calendar'))}}</a></li>
    <li class="active">{{ucfirst(trans('educal.createevent'))}}</li>
  </ol>
</div>
        <div class="col-xs-12 col-sm-9">
            {{ Form::open([
            'route' => 'event.store',
            'data-ajax' => 'false',
            ]), PHP_EOL }}
            <h1>{{ucfirst(trans('educal.createevent'))}}</h1>

            @foreach ($errors->all() as $message)
            {{$message}}
            @endforeach

            <div class="form-group">
                {{Form::label('title', ucfirst(trans('educal.title')))}}
                {{Form::text('title', null , ['class'=>'form-control','placeholder'=>"Event title"])}}
            </div>
            <div class="form-group">
                {{Form::label('group', ucfirst(trans('educal.group')))}}
                {{Form::select('group', $groups, [], array('class'=>'form-control'))}}
            </div>
            <div class="form-group">
                {{Form::label('description', ucfirst(trans('educal.description')))}}
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
                    <input type="checkbox" name="day" id="day"> {{ucfirst(trans('educal.allday'))}}
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="repeat" id="repeat"> {{ucfirst(trans('educal.repeatingevent'))}}
                </label>
            </div>
            <label>{{ucfirst(trans('educal.recurrence'))}}</label>
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
                        {{Form::select('repeat_type', ['d'=>ucfirst(trans('educal.day')),'w'=>ucfirst(trans('educal.week')),'M'=>ucfirst(trans('educal.month')),'y'=>ucfirst(trans('educal.year')) ], [], array('class'=>'form-control'))}}
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
            <button type="submit" class="btn btn-default btn-educal-primary">{{ucfirst(trans('educal.addevent'))}}</button>
            {{ Form::close(), PHP_EOL }}
            {{ Session::get('errorMessage') }}
            <!-- TODO: Toggle visibility of repeat values -->
        </div><!-- /.col-xs-12 main -->
    </div><!--/.row-->

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop