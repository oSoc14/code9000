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
            # Groups
        </th>
        <th>
            Actions
        </th>
    </tr>
    @foreach($schools as $school)
    <tr>
        <td>{{ HTML::linkRoute('school.detail', $school->name, ['id' => $school->id], []) }}</td>
        <td>{{$school->short}}</td>
        <td>{{count($school->groups)}}</td>
        <td>
            <a href="{{route('school.detail',$school->id)}}"><span class="glyphicon glyphicon-eye-open"></span></a>
            <a href="#"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="#"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr>
    @endforeach
</table>

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop