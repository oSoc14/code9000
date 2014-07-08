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
    'as'   => 'landing',
    'uses' => 'HomeController@showWelcome'
]);

Route::get('about', array('as' => 'about', function()
{
    return View::make('about');
}));

Route::get('settings', array('as' => 'settings', function()
{
  return View::make('settings');
}));

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
    Route::get('/', [
        'as' => 'user.index',
        'uses' => 'UserController@index'
    ]);

    Route::get('/{id}', [
        'as' => 'user.show',
        'uses' => 'UserController@show'
    ])->where('id', '[0-9]+');

    Route::post('/auth', [
        'as' => 'user.auth',
        'uses' => 'UserController@auth'
    ]);

    Route::post('/register', [
        'as' => 'user.register',
        'uses' => 'UserController@store'
    ]);

    Route::get('/logout', [
        'as'   => 'user.logout',
        'uses' => 'UserController@logout'
    ]);

    Route::post('/group/add/{group_id}', [
        'as'   => 'user.addToGroup',
        'uses' => 'UserController@addToGroup'
    ])->where('id', '[0-9]+');

    Route::get('/activate/{id}', [
        'as'   => 'user.activate',
        'uses' => 'UserController@activateUser'
    ])->where('id', '[0-9]+');

    Route::get('/edit/{id?}', [
        'as'   => 'user.edit',
        'uses' => 'UserController@editUser'
    ])->where('id', '[0-9]+');

    Route::post('/update/{id}', [
        'as'   => 'user.update',
        'uses' => 'UserController@updateUser'
    ])->where('id', '[0-9]+');

    Route::get('/removeFromGroup/{id}/{groupId}', [
        'as'   => 'user.removeFromGroup',
        'uses' => 'UserController@removeFromGroup'
    ])->where('id', '[0-9]+')
      ->where('groupId', '[0-9]+');
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

    // Shows creation form for events
    Route::get('/event/create', [
        'as'   => 'event.create',
        'uses' => 'CalendarController@create'
    ]);

    // Stores events
    Route::post('/event/create', [
        'as'   => 'event.store',
        'uses' => 'CalendarController@store'
    ]);

    // Shows creation form for events
    Route::get('/event/edit/{id}', [
        'as'   => 'event.edit',
        'uses' => 'CalendarController@edit'
    ])->where('id', '[0-9]+');

    // Stores events
    Route::post('/event/edit/{id}', [
        'as'   => 'event.update',
        'uses' => 'CalendarController@update'
    ])->where('id', '[0-9]+');

    // Deletes the event with the given ID
    Route::get('/event/delete/{id}', [
        'as'   => 'event.delete',
        'uses' => 'CalendarController@destroy'
    ])->where('id', '[0-9]+');

    // Returns detailsview for an event with given ID
    Route::get('/event/{id}', [
        'as'   => 'event.detail',
        'uses' => 'CalendarController@show'
    ])->where('id', '[0-9]+');

    // Returns all events for the users school
    Route::get('/api/events', [
        'as'   => 'calendar.events',
        'uses' => 'CalendarController@events'
    ]);
});

/***
 * Manages all the group routes
 */
Route::group(array('prefix' => 'group'), function()
{
    // Index, lists all groups
    Route::get('/', [
        'as'   => 'group.index',
        'uses' => 'GroupController@index'
    ]);

    // Home
    Route::get('/{id}', [
        'as'   => 'group.edit',
        'uses' => 'GroupController@edit'
    ])->where('id', '[0-9]+');

    // Create a new group
    Route::get('/create', [
        'as'   => 'group.create',
        'uses' => 'GroupController@create'
    ]);

    // Store a new group
    Route::post('/create', [
        'as'   => 'group.store',
        'uses' => 'GroupController@store'
    ]);

    // Update a group
    Route::post('/edit/{id}', [
        'as'   => 'group.update',
        'uses' => 'GroupController@update'
    ])->where('id', '[0-9]+');
});

/**
 * iCal routes
 * returns iCal, .ics file
 */
Route::group(array('prefix' => 'export'), function()
{
    Route::get('/{school}/{class}/ical.ics', [
        'as'   => 'export.group',
        'uses' => 'IcalCalendarController@index'
    ])->where(['school' => '[a-z]+', 'class' => '[a-z]+']);

    Route::get('/appointment/{id}/ical.ics', [
        'as'   => 'export.single',
        'uses' => 'IcalCalendarController@show'
    ])->where('id', '[0-9]+');
});
