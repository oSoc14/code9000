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
            {{Form::label('location', 'Locatie (optioneel)', ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                {{Form::text('location', $event->location , ['class'=>'form-control','placeholder'=>"Locatie"])}}
            </div>
        </div>
<!--
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
-->

        <div class="form-group">
            {{Form::label('datetimepicker1', ucfirst(trans('educal.startdate')), ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                <div class="input-group date" id="date-time-picker1">
                    <div class="input-group-addon date-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                    {{Form::text('start-date', date_format(new DateTime($event->start_date), 'd-m-Y') , ['class'=>'form-control date start','placeholder'=>"Start datum"])}}
                    {{Form::text('end-date', date_format(new DateTime($event->end_date), 'd-m-Y') , ['class'=>'form-control date end','placeholder'=>"Eind datum"])}}
                    <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                    {{Form::text('start-time', date_format(new DateTime($event->start_date), 'h:i') , ['class'=>'form-control time start','placeholder'=>"Start uur"])}}
                    {{Form::text('end-time', date_format(new DateTime($event->end_date), 'h:i') , ['class'=>'form-control time end','placeholder'=>"Eind uur"])}}
                </div>
            </div>
        </div>

        @if($event->parent_id)
        <div class="form-group">
            <div class="col-md-offset-2 col-sm-12 col-md-5">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="par" id="par" {{ ($event->parent_id?'checked':'')}}> Pas alle gelijkaardige activiteiten aan
                    </label>
                </div>
            </div>
        </div>
        @endif

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
    {{ HTML::script('js/jquery-ui-1.11.1.js') }}
    {{ HTML::script('js/jquery-ui.multidatespicker.js') }}
    {{ HTML::script('js/jquery.timepicker.min.js') }}
    {{ HTML::script('js/multical.js') }}
    {{ HTML::script('js/app.js') }}
@stop