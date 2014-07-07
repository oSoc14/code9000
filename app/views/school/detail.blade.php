@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="row">
  <div class="col-xs-12">
    <ol class="breadcrumb">
      <li><a href="{{ route('landing') }}">Home</a></li>
      <li><a href="{{ route('school.index') }}">Schools</a></li>
      <li class="active">Detail</li>
    </ol>
  </div>
</div>
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
            <span class="glyphicon glyphicon-eye-open"></span>&nbsp;
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;
            <span class="glyphicon glyphicon-trash"></span>
        </td>
    </tr>
    @endforeach
</table>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop