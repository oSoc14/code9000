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

Route::get('/schools', function(){
   return View::make('schools');
});
Route::get('/users', function(){
  return View::make('users');
});
Route::get('/groups', function(){
  return View::make('groups');
});
Route::get('/about', function(){
  return View::make('about');
});
Route::get('/settings', function(){
  return View::make('settings');
});

Route::group(['prefix' => 'school'], function () {
    Route::post('/register', [
        'as' => 'school.store',
        'uses' => 'SchoolController@store'
    ]);

    Route::get('/', [
        'as'   => 'school.index',
        'uses' => 'SchoolController@index'
    ]);

    Route::get('/{id}', [
        'as'   => 'school.detail',
        'uses' => 'SchoolController@show'
    ])->where('id', '[0-9]+');

    Route::get('/edit/{id}', [
        'as'   => 'school.edit',
        'uses' => 'SchoolController@edit'
    ])->where('id', '[0-9]+');

    Route::post('/edit/{id}', [
        'as'   => 'school.update',
        'uses' => 'SchoolController@update'
    ])->where('id', '[0-9]+');
});


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


/***
 * Manages all the calendar/event routes
 */
Route::group(array('prefix' => 'calendar'), function()
{
    // Home
    Route::get('/', [
        'as'   => 'calendar.index',
        'uses' => 'CalendarController@index'
    ]);

    //Shows creation form for events
    Route::get('/event/create', [
        'as'   => 'event.create',
        'uses' => 'CalendarController@create'
    ]);

    //Stores events
    Route::post('/event/create', [
        'as'   => 'event.store',
        'uses' => 'CalendarController@store'
    ]);

    //Shows creation form for events
    Route::get('/event/edit/{id}', [
        'as'   => 'event.edit',
        'uses' => 'CalendarController@edit'
    ])->where('id', '[0-9]+');

    //Stores events
    Route::post('/event/edit/{id}', [
        'as'   => 'event.update',
        'uses' => 'CalendarController@update'
    ])->where('id', '[0-9]+');

    //Deletes the event with the given ID
    Route::get('/event/delete/{id}', [
        'as'   => 'event.delete',
        'uses' => 'CalendarController@destroy'
    ])->where('id', '[0-9]+');

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