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
            <th>Activate</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>Name</th>
            <th>Edit</th>
        </tr>
    </tfoot>

    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->email }}</td>
                <td><a href=""><span class="glyphicon glyphicon-pencil"></span></a></td>
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