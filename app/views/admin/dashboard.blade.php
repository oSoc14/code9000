@extends('layout.master-app')

@section('header')
{{ HTML::style("css/calendar.css") }}
{{ HTML::style("css/admin.css") }}
@stop

@section('nav')
<ul>
  <li><a href="{{ route('orgs.index') }}">Dashboard</a></li>
</ul>
@stop

@section('content')
<div ng-app="orgs" ng-controller="OrgController">
  <h1>Dashboard</h1>

  <div class="navbar">
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">Klassen</a></li>
      <li><a href="#">Medewerkers</a></li>
    </ul>
  </div>

  <p>
    <input type="text" ng-model="search.name" class="inp" placeholder="Zoeken op naam">
    <button ng-click="search=null" ng-disabled="!search" class="btn">Reset</button>
  </p>

  <table class="table">
    <thead>
      <tr>
        <th>Actief</th>
        <th>Naam</th>
        <th>url</th>
        <th>Stad</th>
        <th>Taal</th>
        <th>Meer</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="(o, org) in orgs | filter:search">
        <td class="td-checkbox" ng-class="{'td-checkbox--active':org.active}"><label><input type="checkbox" name="name" ng-model="org.active"></label></td>
        <td ng-bind="org.name"></td>
        <td ng-bind="'/'+org.slug"></td>
        <td ng-bind="org.city"></td>
        <td ng-bind="org.lang"></td>
        <td><a href="#">Over</a></td>
      </tr>
    </tbody>
  </table>

  <p ng-hide="(orgs | filter:search).length">
    Geen resultaten
  </p>
</div>
@stop

@section('footerScript')
{{ HTML::script('bower_components/angular/angular.min.js') }}
{{ HTML::script('bower_components/angular-resource/angular-resource.min.js') }}
<script type="text/javascript">
angular.module('orgs', ['ngResource'])
  .config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
  })
  .controller('OrgController', ['$scope', '$resource', '$http', function($scope, $resource, $http) {

    // Resources
    var Orgs = $resource('{{ route('api.orgs') }}');
    $scope.orgs = Orgs.query();

  }]);
</script>
@stop
