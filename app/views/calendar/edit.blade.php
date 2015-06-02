@extends('layout.master')

@section('header')
    {{ HTML::style("css/app.css") }}
    {{ HTML::style("css/jquery.datetimepicker.css") }}
    {{ HTML::style("css/jquery.timepicker.css") }}
@stop

@section('content')
    <div class="col-xs-12 col-sm-9">
        <a href="{{ route('calendar.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.calendar')]))}}</a>
        <h1>{{ucfirst(trans('educal.editevent'))}}</h1>
        {{Form::open(array('route' => array('event.update',$event->id), 'class'=>'form-horizontal')) }}

        @if($errors->count())
            <div class="alert alert-danger" role="alert">
                <strong>{{ucfirst(trans('educal.errors'))}}</strong>
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{$message}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            {{Form::label('title', ucfirst(trans('educal.title')), array('class'=>'col-sm-12 col-md-2 control-label')) }}
            <div class="col-sm-12 col-md-10">
                {{Form::text('title', $event->title , ['class'=>'form-control','placeholder'=>"What's the title of your event?"])}}
            </div>
        </div>
        <div class="form-group">
            {{Form::label('group', ucfirst(trans('educal.group')), array('class'=>'col-sm-12 col-md-2 control-label'))}}
            <div class="col-sm-12 col-md-10">
                {{Form::select('group', $groups, $event->group_id, array('class'=>'form-control'));}}
            </div>
        </div>
        <div class="form-group">
            {{Form::label('description', ucfirst(trans('educal.description')), array('class'=>'col-sm-12 col-md-2 control-label'))}}
            <div class="col-sm-12 col-md-10">
                {{Form::textarea('description', $event->description , ['class'=>'form-control','placeholder'=>"Enter some details about the event", 'rows'=>3])}}
            </div>
        </div>

        <div class="form-group">
            {{Form::label('datetimepicker1', ucfirst(trans('educal.startdate')), array('class'=>'col-sm-12 col-md-2 control-label'))}}
            <div class="col-sm-12 col-md-10">
                <div class='input-group date'>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {{Form::text('start', date_format(new DateTime($event->start_date), 'Y/m/d H:i') , ['class'=>'form-control','id'=>'datetimepicker1'])}}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{Form::label('datetimepicker2', ucfirst(trans('educal.enddate')), array('class'=>'col-sm-12 col-md-2 control-label'))}}
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
                        <input name="day" type="checkbox" {{ ($event->allday?'checked':'')}}> {{ucfirst(trans('educal.allday'))}}
                    </label>
                </div>
            </div>
            <div class="col-md-offset-2 col-sm-12 col-md-5">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="repeat" id="repeat" {{ ($event->nr_repeat?'checked':'')}}> {{ucfirst(trans('educal.repeatingevent'))}}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-repeat-container">
            <div class="form-group">
                <label for="repeat_freq" class="col-xs-12 col-sm-12 col-md-2 control-label">{{ucfirst(trans('educal.every'))}}...</label>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group">
                        <input type="number" id="repeat_freq" name="repeat_freq" class="form-control" value="{{ $event->repeat_freq }}"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-cog"></span></span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-3">
                    {{Form::select('repeat_type', ['d'=>ucfirst(trans('educal.days')),'w'=>ucfirst(trans('educal.weeks')),'M'=>ucfirst(trans('educal.months')),'y'=>ucfirst(trans('educal.years')) ], $event->repeat_type, array('class'=>'form-control', 'id'=>'repeat_type'))}}
                </div>
            </div>

            <div class="form-group">
                <label for="datetimepicker3" class="col-sm-12 col-md-2 control-label">{{ucfirst(trans('educal.until'))}}...</label>
                <div class="col-sm-12 col-md-6">
                    <div class='input-group date'>
                        {{Form::text('recurrence_end', null , ['class'=>'form-control','id'=>'datetimepicker3'])}}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="nr_repeat" name="nr_repeat" value="{{ $event->nr_repeat }}"/>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-educal-primary"><i class="fa fa-save"></i> {{ucfirst(trans('educal.savechanges'))}}</button>
                <a data-toggle="modal" data-target="#confirm-delete" type="button" class="btn btn-default btn-educal-danger" href="#" data-href="{{route('event.delete',$event->id)}}" id="deleteEvent">
                    <i class="fa fa-times-circle"></i> {{ ucfirst(trans('educal.deleteevent'))}}
                </a>
            </div>
        </div>

        {{ Form::close(), PHP_EOL }}
        {{ Session::get('errorMessage') }}

    </div><!-- /.col-xs-12 main -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{ucfirst(trans('educal.confirmation'))}}
                </div>
                <div class="modal-body">
                    {{ucfirst(trans('educal.confirmationmsg'))}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-educal-warning" data-dismiss="modal">{{ucfirst(trans('educal.cancel'))}}</button>
                    <a href="#" class="btn btn-educal-danger"><i class="fa fa-times-circle"></i> {{ucfirst(trans('educal.confirmation'))}}</a>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footerScript')
    {{ HTML::script('js/app.js') }}
@stop