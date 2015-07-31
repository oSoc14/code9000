@extends('layout.master-app')

@section('header')
{{ HTML::style("css/admin.css") }}
@stop

@section('content')
<div ng-app="orgs" ng-controller="OrgController">
  <h1>Dashboard</h1>

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
          var Orgs = $resource('{{ route('api.org.list') }}');
    $scope.orgs = Orgs.query();

  }]);
</script>
@stop
