@extends('layout.master-app')

@section('header')
{{ HTML::style("css/calendar.css") }}
{{ HTML::style("css/admin.css") }}
@stop

@section('content')
<div ng-app="users" ng-controller="UserController">
  <h1>Medewerkers</h1>

  @include('admin/navbar')

  <p>
    <input type="text" ng-model="search.name" class="inp" placeholder="Zoeken op naam">
    <button ng-click="search=null" ng-disabled="!search" class="btn">Reset</button>
  </p>

  <table class="table">
    <thead>
      <tr>
        <th>Actief</th>
        <th>Naam</th>
        <th>Email</th>
        <th>Kalenders</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="(o, user) in users | filter:search">
        <td class="td-checkbox" ng-class="{'td-checkbox--active':user.activated}"><label><input type="checkbox" name="name" ng-model="user.activated"></label></td>
        <td ng-bind="user.first_name+' '+user.last_name"></td>
        <td ng-bind="user.email"></td>
        <td>Kalenders...</td>
      </tr>
    </tbody>
  </table>

  <p ng-hide="(users | filter:search).length">
    Geen resultaten
  </p>
</div>
@stop

@section('footerScript')
{{ HTML::script('bower_components/angular/angular.min.js') }}
{{ HTML::script('bower_components/angular-resource/angular-resource.min.js') }}
<script type="text/javascript">
angular.module('users', ['ngResource'])
  .config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
  })
  .controller('UserController', ['$scope', '$resource', '$http', function($scope, $resource, $http) {

    // Resources
    var users = $resource('{{ route('api.users') }}');
    $scope.users = users.query();

  }]);
</script>
@stop
