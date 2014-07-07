@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>
    Add Group
</h1>
{{Form::open(array('route' => array('group.store')))}}
@if($schools)
<div class="form-group">
    <label for="school">School</label>
    {{Form::select('school', $schools, [], array('class'=>'form-control'));}}
</div>
@endif
<div class="form-group">
    <label for="user">Group name</label>
    <input  type="text" name="name" class="form-control"/>
</div>
<button type="submit" class="btn btn-primary">Add Group</button>
{{ Form::close(), PHP_EOL }}
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