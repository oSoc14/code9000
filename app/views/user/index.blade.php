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
            <th>Surname</th>
            <th>Email</th>
            <th>Activate</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Activate</th>
        </tr>
    </tfoot>

    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a data-userid="{{$user->id}}" class="activateUser" href="#">
                        <!-- TODO: fix colors -->
                        @if($user->activated == 1)
                            <span class="green glyphicon glyphicon-thumbs-up"></span>
                        @else
                            <span class="red glyphicon glyphicon-thumbs-down"></span>
                        @endif
                    </a>
                    <span class="loader glyphicon glyphicon-cog"></span>
                </td>
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