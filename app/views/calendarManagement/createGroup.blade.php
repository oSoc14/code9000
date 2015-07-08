@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-10">
          <a href="{{ route('calendarManagement.index') }}" class="link-goback"><i class="fa fa-angle-double-left"></i> {{ucfirst(trans('educal.backto',['page'=>trans('educal.groups')]))}}</a>
            <h1>{{ucfirst(trans('educal.addgroup'))}}</h1>
            {{ Form::open(array('route' => array('group.store'),'class'=>'form form-horizontal')) }}

            <!-- ERROR MESSAGES -->
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
                {{Form::label('name', ucfirst(trans('educal.name')), array('class'=>'col-md-2 control-label')) }}
                <div class="col-md-8">
                    {{Form::text('name', null , ['class'=>'form-control', 'placeholder'=>'For example: "administrators"'])}}
                </div>
            </div>
            @if($schools && Sentry::getUser()->hasAccess('school'))
            <div class="form-group">
                <label for="school" class="col-md-2 control-label">{{ucfirst(trans('educal.whichschool'))}}</label>
                <div class="col-md-8">
                    {{Form::select('school', $schools, [], array('id'=>'school', 'class'=>'form-control'));}}
                </div>
            </div>
            @elseif(Sentry::getUser()->hasAccess('school'))
            <p>{{ucfirst(trans('educal.noschools'))}}</p>
            @endif


            <div class="form-group">
                <label class="col-md-2 control-label">{{ucfirst(trans('educal.permissions'))}}</label>
                <div class="col-md-8">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="permissions[group]"> {{ucfirst(trans('educal.managegroups'))}}
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="permissions[user]"> {{ucfirst(trans('educal.manageusers'))}}
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="permissions[event]" checked> {{ucfirst(trans('educal.manageevents'))}}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-plus"></i> {{ucfirst(trans('educal.creategroup'))}}</button>
                </div>
            </div>

            {{ Form::close(), PHP_EOL }}


        </div>
    </div>
</div>


@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}
{{ HTML::style('packages/datatables/css/jquery.dataTables.min.css') }}
<script>
    $(document).ready(function() {
        $('#userTable').dataTable();
    } );
</script>
@stop