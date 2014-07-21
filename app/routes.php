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

if(Session::has('lang')){
    //Set the language
    App::setLocale(Session::get('lang'));
}

Route::get('/', [
    'as'   => 'landing',
    'uses' => 'HomeController@showWelcome'
]);

// About page
Route::get('about', ['as' => 'about', function() {
    return View::make('about');
}]);

Route::group(['prefix' => 'school'], function () {
    // Create a new school
    Route::post('/register', [
        'as' => 'school.store',
        'uses' => 'SchoolController@store'
    ]);

    // List all schools
    Route::get('/', [
        'as'   => 'school.index',
        'uses' => 'SchoolController@index'
    ]);

    // Show details of a certain school
    Route::get('/{id}', [
        'as'   => 'school.detail',
        'uses' => 'SchoolController@show'
    ])->where('id', '[0-9]+');

    // Show the view to edit a school
    Route::get('/edit/{id}', [
        'as'   => 'school.edit',
        'uses' => 'SchoolController@edit'
    ])->where('id', '[0-9]+');

    // Update a school
    Route::post('/edit/{id}', [
        'as'   => 'school.update',
        'uses' => 'SchoolController@update'
    ])->where('id', '[0-9]+');

    // Delete a school
    Route::get('/delete/{id}', [
        'as'   => 'school.delete',
        'uses' => 'SchoolController@destroy'
    ])->where('id', '[0-9]+');
});


Route::group(['prefix' => 'user'], function () {
    // Show list of users
    Route::get('/', [
        'as' => 'user.index',
        'uses' => 'UserController@index'
    ]);

    // Authenticate user
    Route::post('/auth', [
        'as' => 'user.auth',
        'uses' => 'UserController@auth'
    ]);

    // Register as a new user
    Route::post('/register', [
        'as' => 'user.register',
        'uses' => 'UserController@store'
    ]);

    // Create a new user (backoffice side)
    Route::post('/create', [
        'as' => 'user.create',
        'uses' => 'UserController@create'
    ]);

    // Log user out
    Route::get('/logout', [
        'as'   => 'user.logout',
        'uses' => 'UserController@logout'
    ]);

    // Add user to group
    Route::post('/group/add/{group_id}', [
        'as'   => 'user.addToGroup',
        'uses' => 'UserController@addToGroup'
    ])->where('id', '[0-9]+');

    // Active a user
    Route::get('/activate/{id}', [
        'as'   => 'user.activate',
        'uses' => 'UserController@activateUser'
    ])->where('id', '[0-9]+');

    // Show the view to edit a user
    Route::get('/edit/{id?}', [
        'as'   => 'user.edit',
        'uses' => 'UserController@editUser'
    ])->where('id', '[0-9]+');

    // Update a user
    Route::post('/update/{id}', [
        'as'   => 'user.update',
        'uses' => 'UserController@updateUser'
    ])->where('id', '[0-9]+');

    // Remover a user from group
    Route::get('/removeFromGroup/{id}/{groupId}', [
        'as'   => 'user.removeFromGroup',
        'uses' => 'UserController@removeFromGroup'
    ])->where('id', '[0-9]+')
        ->where('groupId', '[0-9]+');

    // Destroy a user
    Route::get('/delete/{id}', [
        'as'   => 'user.removeUserFromSchool',
        'uses' => 'UserController@removeUserFromSchool'
    ])->where('id', '[0-9]+');
});


/***
 * Manages all the calendar/event routes
 */
Route::group(['prefix' => 'calendar'], function()
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

    // Returns all events for the users school
    Route::get('/api/events', [
        'as'   => 'calendar.events',
        'uses' => 'CalendarController@events'
    ]);
});

/***
 * Manages all the group routes
 */
Route::group(['prefix' => 'group'], function()
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

    // Destroy a group
    Route::get('/delete/{id}', [
        'as'   => 'group.delete',
        'uses' => 'GroupController@destroy'
    ])->where('id', '[0-9]+');
});

/**
 * iCal routes and pdf-routes
 * returns iCal, .ics file or .pdf file
 */
Route::group(['prefix' => 'export'], function()
{
    // iCal Export route for a certain class
    Route::get('/{school}/{class}', [
        'as'   => 'export.group',
        'uses' => 'IcalCalendarController@index'
    ])->where(['school' => '[a-z]+', 'class' => '[a-z]+']);

    // iCal Export route for a single appointment
    Route::get('/appointment/{id}', [
        'as'   => 'export.single',
        'uses' => 'IcalCalendarController@show'
    ])->where('id', '[0-9]+');

    // PDF Export route for a certain class
    Route::get('/pdf/{school}/{class}', [
        'as'   => 'export.group',
        'uses' => 'PdfCalendarController@index'
    ])->where(['school' => '[a-z]+', 'class' => '[a-z]+']);

    // PDF Export route for a single appointment
    Route::get('/appointment/pdf/{id}', [
        'as'   => 'export.singlepdf',
        'uses' => 'PdfCalendarController@show'
    ])->where('id', '[0-9]+');
});