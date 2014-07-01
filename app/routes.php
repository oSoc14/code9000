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

Route::get('/home', function()
{
    return View::make('home');
});

Route::get('/events', function(){
  return View::make('events');
});

Route::get('/about', function(){
  return View::make('about');
});

Route::get('/users', function(){
  return View::make('users');
});

Route::get('/schools', function(){
  return View::make('schools');
});

Route::get('/groups', function(){
  return View::make('groups');
});

Route::get('/settings', function(){
  return View::make('settings');
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

Route::group(array('prefix' => 'calendar'), function()
{
    //home
    Route::get('/', [
        'as'   => 'calendar.index',
        'uses' => 'CalendarController@index'
    ]);
});