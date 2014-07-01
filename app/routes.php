<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', [
    'as'   => 'index',
    'uses' => 'UserController@index'
]);

Route::group(['prefix' => 'user'], function () {
    Route::post('/auth', [
        'as' => 'user.auth',
        'uses' => 'UserController@auth'
    ]);

    Route::get('/logout', [
        'as'   => 'user.logout',
        'uses' => 'UserController@logout'
    ]);
});

Route::group(array('prefix' => 'calendar'), function()
{
    //home
    Route::get('/', [
        'as'   => 'calendar.index',
        'uses' => 'CalendarController@index'
    ]);

    //Shows the selected day's events
    Route::get('/event/create', [
        'as'   => 'event.create',
        'uses' => 'CalendarController@create'
    ]);

    //Shows the selected day's events
    Route::get('/events', [
        'as'   => 'calendar.list',
        'uses' => 'CalendarController@listView'
    ]);

    //Returns all events for the users school
    Route::get('/api/events', [
        'as'   => 'calendar.events',
        'uses' => 'CalendarController@events'
    ]);
});