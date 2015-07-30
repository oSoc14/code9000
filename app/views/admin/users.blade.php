@extends('layout.master-app')

@section('header')
{{ HTML::style("css/calendar.css") }}
{{ HTML::style("css/admin.css") }}
@stop

@section('content')
<form ng-app="users" ng-controller="UserController">
  <h1>Medewerkers</h1>

  @include('admin/navbar')

  <p>
    <button type="button" ng-click="adding=1">Medewerker toevoegen</button>
    <input type="text" ng-model="search" class="inp" placeholder="Zoeken">
    <button ng-click="search=null" ng-disabled="!search" class="btn">Reset</button>
  </p>

  <table class="table">
    <thead>
      <tr>
        <th>Actief</th>
        <th>Voornaam</th>
        <th>Naam</th>
        <th>Email</th>
        <th>Kalenders</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-show="adding" class="dirty" ng-class="{saved:adduser.saved,error:adduser.error}">
        <td class="td-checkbox" ng-class="{'td-checkbox--active':adduser.activated}"><label><input type="checkbox" name="name" ng-model="adduser.activated"></label></td>
        <td class="td-inp"><label><input type="text" ng-model="adduser.first_name" placeholder="Vul voornaam in"></label></td>
        <td class="td-inp"><label><input type="text" ng-model="adduser.last_name" placeholder="Vul naam in"></label></td>
        <td class="td-inp"><label><input type="email" ng-model="adduser.email" placeholder="Vul e-mailadres in"></label></td>
        <td class="td-btn" ng-click="addnew(user)">Bewaren</td>
      </tr>
      <tr ng-repeat="(u, user) in users | filter:search" ng-class="{saved:user.saved,error:user.error,dirty:user.dirty}">
        <td class="td-checkbox" ng-class="{'td-checkbox--active':user.activated}"><label><input type="checkbox" name="name" ng-model="user.activated"></label></td>
        <td class="td-inp"><label><input type="text" ng-model="user.first_name" ng-blur="save(u, user)" ng-change="user.dirty=1"></label></td>
        <td class="td-inp"><label><input type="text" ng-model="user.last_name" ng-blur="save(u, user)" ng-change="user.dirty=1"></label></td>
        <td class="td-inp"><label><input type="email" ng-model="user.email" ng-blur="save(u, user)" ng-change="user.dirty=1"></label></td>
        <td ng-click="open(user)">Kalenders...</td>
      </tr>
    </tbody>
  </table>

  <p ng-hide="(users | filter:search).length">
    Geen resultaten
  </p>
  <div id="overlay" ng-show="overlay" ng-click="overlay=0" class="ng-cloak">
    <div class="modal-container">
      <div class="modal-body" ng-click="$event.stopPropagation();">
        <div class="close" ng-click="overlay=0">&times;</div>
        <h1 ng-bind="user.first_name+' '+user.last_name" ng-click="overlay=0" class="modal-title"></h1>
        <table class="table">
          <thead>
            <tr>
              <th>Toegang</th>
              <th>Kalender</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="(c, cal) in cals | filter:search" ng-class="{saved:user.saved,error:user.error,dirty:user.dirty}">
              <td class="td-checkbox" ng-class="{'td-checkbox--active':user.activated}"><label><input type="checkbox" name="name" ng-model="user.activated"></label></td>
              <td class="td-inp"><label><input type="text" ng-model="user.first_name" ng-blur="save(u, user)" ng-change="user.dirty=1"></label></td>
              <td class="td-inp"><label><input type="text" ng-model="user.last_name" ng-blur="save(u, user)" ng-change="user.dirty=1"></label></td>
              <td class="td-inp"><label><input type="email" ng-model="user.email" ng-blur="save(u, user)" ng-change="user.dirty=1"></label></td>
              <td ng-click="open(user)">Kalenders...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</form>
@stop

@section('footerScript')
{{ HTML::script('bower_components/angular/angular.min.js') }}
{{ HTML::script('bower_components/angular-resource/angular-resource.min.js') }}
<script type="text/javascript">
angular.module('users', ['ngResource'])
  .config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
  })
  .controller('UserController', ['$scope', '$resource', '$http', '$timeout', function($scope, $resource, $http, $timeout) {

    // Resources
          var Users = $resource('{{ route('api.currentorg.users') }}/:id', {
        id: '@id'
      });
    $scope.users = Users.query();
    $scope.overlay = 0;

    $scope.addnew = function(user) {
      Users.save(user);
    }

    $scope.open = function(user) {
      $scope.user = user;
      $scope.overlay = 1;
    }

    $scope.save = function(u, user) {
      if (!user.dirty) return;
      user.dirty = false;

      // Hack
      user.lang = 'nl';
      user.name = 'apierror';
      user.surname = 'apierror';

      user.$save(function(a, b) {
        user.saved = true;
        $timeout(function(){
          user.saved = false;
        }, 100);
      }, function(a, b) {
        user.error = true;
      });
    };

  }]);

window.addEventListener('submit', function(e){
  e.preventDefault();
});
</script>
@stop
