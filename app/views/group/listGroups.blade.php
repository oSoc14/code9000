@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>
    {{ $schoolName }}
</h1>
<table id="groupTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>Name</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </tfoot>

    <tbody>
        @foreach($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td><a href="{{route('group.edit',$group->id)}}"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td><a href="#"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}
{{ HTML::style('packages/datatables/css/jquery.dataTables.min.css') }}
<script>
    $(document).ready(function() {
        $('#groupTable').dataTable();
    } );
</script>
@stop