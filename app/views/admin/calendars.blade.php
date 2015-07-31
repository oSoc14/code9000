@extends('layout.master-app')

@section('header')
    {{ HTML::style("css/admin.css") }}
@stop

@section('content')
    <div ng-app="calendars" ng-controller="CalendarController">
        <h1>Klassen</h1>

        @include('admin/navbar')

        <p class="actionbar">
            <button type="button" ng-click="adding=1" class="btn">Klas toevoegen</button>
            <input type="text" ng-model="search.name" class="inp" placeholder="Zoeken op naam">
            <button ng-click="search=null" ng-disabled="!search" class="btn">Reset</button>
        </p>

        <table class="table">
            <thead>
            <tr>
                <th>Kleur</th>
                <th width="3em">url</th>
                <th>Naam</th>
                <th>Beschrijving</th>
                <th width="10em">parent</th>
                <th>Meer</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-show="adding" class="dirty" ng-class="{saved:addcal.saved,error:addcal.error}">
                <td class="td-inp"><label><input type="color" ng-model="addcal.color" placeholder="#123456 (voorbeeld)"></label>
                </td>
                <td class="td-inp"><label><input type="text" ng-model="addcal.slug" placeholder="Vul slug in"></label>
                </td>
                <td class="td-inp"><label><input type="text" ng-model="addcal.name"
                                                 placeholder="Vul klasnaam in"></label></td>
                <td class="td-inp"><label><input type="text" ng-model="addcal.description"
                                                 placeholder="Korte beschrijving"></label></td>
                <td class="td-inp"><label><select ng-options="a.id as a.name for a in cals"
                                                  ng-model="cal.parent_id"></select></label></td>
                <td class="td-btn" ng-click="addnew(addcal)">Bewaren</td>
            </tr>
            <tr ng-repeat="(c, cal) in cals | filter:search"
                ng-class="{saved:cal.saved,error:cal.error,dirty:cal.dirty}">
                <td class="td-inp"><label><input type="color" ng-model="cal.color" ng-blur="save(c, cal)"
                                                 ng-change="cal.dirty=1"></label></td>
                <td class="td-inp"><label><input type="text" ng-model="cal.slug" ng-blur="save(c, cal)"
                                                 ng-change="cal.dirty=1"></label></td>
                <td class="td-inp"><label><input type="text" ng-model="cal.name" ng-blur="save(c, cal)"
                                                 ng-change="cal.dirty=1"></label></td>
                <td class="td-inp"><label><input type="text" ng-model="cal.description" ng-blur="save(c, cal)"
                                                 ng-change="cal.dirty=1"></label></td>
                <td class="td-inp"><label><select ng-options="a.id as a.name for a in cals"
                                                  ng-model="cal.parent_id"></select></label></td>
                <td class="td-btn" ng-click="open(cal)">Bekijken</td>
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

                .config(function ($interpolateProvider) {
                    $interpolateProvider.startSymbol('[[').endSymbol(']]');
                })

                .controller('CalendarController', ['$scope', '$resource', '$http', '$timeout', function ($scope, $resource, $http, $timeout) {

                    // Resources
                    var Cals = $resource('{{ route('api.currentorg.calendars') }}');
                    $scope.cals = Cals.query();

                    $scope.overlay = 0;

                    $scope.addnew = function (cal) {
                        Cals.save(cal, function (u) {
                            $scope.addcal = {};
                            $scope.cals.push(u);
                        });
                    }

                    $scope.save = function (u, cal) {
                        if (!cal.dirty) return;
                        cal.dirty = false;

                        cal.$save(function (a, b) {
                            cal.saved = true;
                            $timeout(function () {
                                cal.saved = false;
                            }, 100);
                        }, function (a, b) {
                            cal.error = true;
                        });
                    };
                }]);

        window.addEventListener('submit', function (e) {
            e.preventDefault();
        });
    </script>
@stop
