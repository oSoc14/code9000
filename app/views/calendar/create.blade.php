@extends('layout.master')

@section('header')
    {{ HTML::style("css/app.css") }}
    {{ HTML::style("css/jquery.datetimepicker.css") }}
    {{ HTML::style("css/jquery.timepicker.css") }}
@stop

@section('content')
    <div class="col-xs-12 col-sm-9">
        <a href="{{ route('calendar.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.calendar')]))}}</a>
        <h1>{{ucfirst(trans('educal.createevent'))}}</h1>

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

        {{ Form::open([
        'route' => 'event.store',
        'data-ajax' => 'false',
        'class'=>'form-horizontal'
        ]), PHP_EOL }}

        <div class="form-group">
            {{Form::label('title', ucfirst(trans('educal.title')), ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                {{Form::text('title', null , ['class'=>'form-control','placeholder'=>"Titel"])}}
            </div>
        </div>

        <div class="form-group">
            {{Form::label('group', ucfirst(trans('educal.group')), ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                {{Form::select('group', $groups, [], ['class'=>'form-control'])}}
            </div>
        </div>

        <div class="form-group">
            {{Form::label('description', ucfirst(trans('educal.description')), ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                {{Form::textarea('description', null , ['class'=>'form-control','placeholder'=>"Beschrijving", 'rows'=>3])}}
            </div>
        </div>

        <div class="form-group">
            {{Form::label('location', 'Locatie (optioneel)', ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                {{Form::text('location', null , ['class'=>'form-control','placeholder'=>"Locatie"])}}
            </div>
        </div>

        <div class="form-group">
            <!-- <div class="col-md-offset-2 col-sm-12 col-md-5">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="day" id="day"> {{ucfirst(trans('educal.allday'))}}
                    </label>
                </div>
            </div> -->
            <div class="col-md-offset-2 col-sm-12 col-md-5">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="repeat" id="repeat">
                        {{ucfirst(trans('educal.repeatingevent'))}}
                        (<a data-toggle="modal" data-target="#year-modal" href="#" data-href="" title="Year Calendar">toon jaarkalender</a>)
                    </label>
                </div>
            </div>
        </div>

        <input type="hidden" id="repeat-dates" name="repeat-dates" value=""/>

        <div class="form-group">
            {{Form::label('datetimepicker1', ucfirst(trans('educal.startdate')), ['class'=>'col-sm-12 col-md-2 control-label'])}}
            <div class="col-sm-12 col-md-10">
                <div class="input-group date" id="date-time-picker1">
                    <div class="input-group-addon date-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                    {{Form::text('start-date', null , ['class'=>'form-control date start','placeholder'=>"Start datum"])}}
                    {{Form::text('end-date', null , ['class'=>'form-control date end','placeholder'=>"Eind datum"])}}
                    <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                    {{Form::text('start-time', null , ['class'=>'form-control time start','placeholder'=>"Start uur"])}}
                    {{Form::text('end-time', null , ['class'=>'form-control time end','placeholder'=>"Eind uur"])}}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-educal-primary"><i class="fa fa-save"></i> {{ucfirst(trans('educal.createevent'))}}</button>
            </div>
        </div>
        {{ Form::close(), PHP_EOL }}
        {{ Session::get('errorMessage') }}
    </div>


    <div class="modal fade" id="year-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Jaarkalender
                </div>
                <div class="modal-body year-cal">
                    <div id="full-year" class="box"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-educal-primary" data-dismiss="modal">{{ucfirst(trans('educal.confirm'))}}</button>
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