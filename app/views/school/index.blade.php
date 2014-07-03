@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>Schools</h1>
<table class="table table-striped">
    <tr>
        <th>
            Name
        </th>
        <th>
            Short name
        </th>
        <th>
            Actions
        </th>
    </tr>
    @foreach($schools as $school)
    <tr>
        <td>{{$school->name}}</td>
        <td>{{$school->short}}</td>
        <td>
            <span class="glyphicon glyphicon-eye-open"></span>
            <span class="glyphicon glyphicon-pencil"></span>
            <span class="glyphicon glyphicon-trash"></span>
        </td>
    </tr>
    @endforeach
</table>

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop