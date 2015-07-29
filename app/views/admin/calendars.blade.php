@extends('layout.master-app')

@section('header')
{{ HTML::style("css/calendar.css") }}
{{ HTML::style("css/admin.css") }}
@stop

@section('content')
<div ng-app="calendars" ng-controller="CalendarController">
  <h1>Klassen</h1>

  @include('admin/navbar')

  <p>
    <input type="text" ng-model="search.name" class="inp" placeholder="Zoeken op naam">
    <button ng-click="search=null" ng-disabled="!search" class="btn">Reset</button>
  </p>

  <table class="table">
    <thead>
      <tr>
        <th>Kleur</th>
        <th>Naam</th>
        <th>Info</th>
        <th>url</th>
        <th>parent</th>
        <th>Medewerkers</th>
        <th>Meer</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="(c, cal) in cals | filter:search">
        <td ng-bind="cal.color"></td>
        <td ng-bind="cal.name"></td>
        <td ng-bind="cal.description"></td>
        <td ng-bind="'/'+cal.slug"></td>
        <td ng-bind="cal.parent_id"></td>
        <td>Medewerkers...</td>
        <td><a href="#">Bekijken</a></td>
      </tr>
    </tbody>
  </table>

  <p ng-hide="(cals | filter:search).length">
    Geen resultaten
  </p>
</div>
@stop

@section('footerScript')
{{ HTML::script('bower_components/angular/angular.min.js') }}
{{ HTML::script('bower_components/angular-resource/angular-resource.min.js') }}
<script type="text/javascript">
angular.module('calendars', ['ngResource'])
  .config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
  })
  .controller('CalendarController', ['$scope', '$resource', '$http', function($scope, $resource, $http) {

    // Resources
          var Cals = $resource('{{ route('api.currentorg.calendars') }}');
    $scope.cals = Cals.query();

  }]);
</script>
@stop
