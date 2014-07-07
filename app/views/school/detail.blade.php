@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>{{$school->name}}</h1>
<table class="table table-striped">
    <tr>
        <th>
            Group
        </th>
        <th>
            Actions
        </th>
    </tr>
    @foreach($school->groups as $group)
    <tr>
        <td>{{ HTML::linkRoute('group.edit', $group->name, ['id' => $group->id], []) }}</td>
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