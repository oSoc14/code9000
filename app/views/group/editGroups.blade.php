@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<h1>
    {{ $group->name }}
</h1>
{{Form::open(array('route' => array('user.addToGroup',$group->id)))}}

<h2>Add User</h2>
<div class="form-group">
    @if(count($smartUsers) > 0)
    <label for="user">User</label>
    {{Form::select('user', $smartUsers, [], array('class'=>'form-control'));}}
    <button type="submit" class="btn btn-primary">Add User</button>
    @else
    <p>Geen gebruikers die kunnen toegevoegd worden</p>
    @endif
    {{ Form::close(), PHP_EOL }}
</div>
<table id="userTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>E-mail</th>
            <th>Name</th>
            <th>Permissions</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>E-mail</th>
            <th>Name</th>
            <th>Permissions</th>
        </tr>
    </tfoot>

    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->email }}</td>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            <td>
                <a class="editUser" href="{{ route('user.edit',$user->id) }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a data-userid="{{$user->id}}" data-url="{{ route('user.removeFromGroup',array('userId' => $user->id,'groupId' => $group->id)) }}" class="removeUserFromGroup" href="#">
                    <span class="glyphicon glyphicon-remove-sign"></span>
                </a>
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
        $('#userTable').dataTable();
    } );
</script>
@stop