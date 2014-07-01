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
//home
Route::get('/', [
    'as'   => 'index',
    'uses' => 'UserController@index'
]);

Route::get('/home', function()
{
  return View::make('home');
});

Route::post('user/auth', [
    'as' => 'user.auth',
    'uses' => 'UserController@auth'
]);


Route::group(array('prefix' => 'calendar'), function()
{
    //home
    Route::get('/', [
        'as'   => 'calendar.index',
        'uses' => 'CalendarController@index'
    ]);
});