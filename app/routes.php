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

Route::get('/', function()
{
  // if logged out --> go to login
  return View::make('login');
  // if logged in  --> go to home

});

Route::get('/home', function()
{
  // only view if logged in & is admin!
  return View::make('home');
});

Route::get('/events', function()
{
  // only view if logged in & is admin!
  return View::make('events');
});

Route::post('user/auth', [
    'as' => 'user.auth',
    'uses' => 'UserController@auth'
]);