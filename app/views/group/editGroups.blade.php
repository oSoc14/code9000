@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<table id="example" class="display" cellspacing="0" width="100%">
<thead>
<tr>
    <th>Name</th>
    <th>Last Name</th>
    <th>E-mail</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>
</thead>

<tfoot>
<tr>
    <th>Name</th>
    <th>Last Name</th>
    <th>E-mail</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>
</tfoot>

<tbody>
<tr>
    <td>Tiger Nixon</td>
    <td>System Architect</td>
    <td>Edinburgh</td>
    <td></td>
    <td>2011/04/25</td>
</tr>
<tr>
    <td>Garrett Winters</td>
    <td>Accountant</td>
    <td>Tokyo</td>
    <td>63</td>
    <td>2011/07/25</td>
</tr>
<tr>
    <td>Ashton Cox</td>
    <td>Junior Technical Author</td>
    <td>San Francisco</td>
    <td>66</td>
    <td>2009/01/12</td>
</tr>
<tr>
    <td>Cedric Kelly</td>
    <td>Senior Javascript Developer</td>
    <td>Edinburgh</td>
    <td>22</td>
    <td>2012/03/29</td>
</tr>
<tr>
    <td>Airi Satou</td>
    <td>Accountant</td>
    <td>Tokyo</td>
    <td>33</td>
    <td>2008/11/28</td>
</tr>
</tbody>
</table>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
{{ HTML::script('packages/datatables/js/jquery.dataTables.min.js') }}
{{ HTML::style('packages/datatables/css/jquery.dataTables.min.css') }}
<script>
    $(document).ready(function() {
        $('#example').dataTable();
    } );
</script>
@stop