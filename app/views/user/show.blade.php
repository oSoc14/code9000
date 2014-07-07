@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
@if($user->school)
<h1>{{$user->school->name}}</h1>
@else
<h1>Super admin</h1>
@endif
<table class="table table-striped">
    <tr>
        <th>
            Group
        </th>
        <th>
            Actions
        </th>
    </tr>
    @foreach($user->groups as $group)
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