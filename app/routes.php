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
Route::get('about', ['as' => 'about', 'before' => 'auth', function() {
    return View::make('about');
}]);

// Info page
Route::get('help', ['as' => 'help', 'before' => 'auth', function() {
  return View::make('help');
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
        'uses' => 'SchoolController@index',
        'before' => 'admin'
    ]);

    // Show details of a certain school
    Route::get('/{id}', [
        'as'   => 'school.detail',
        'uses' => 'SchoolController@show',
        'before' => 'admin'
    ])->where('id', '[0-9]+');

    // Show the view to edit a school
    Route::get('/edit/{id}', [
        'as'   => 'school.edit',
        'uses' => 'SchoolController@edit'
    ])->where('id', '[0-9]+');

    // Update a school
    Route::post('/edit/{id}', [
        'as'   => 'school.update',
        'uses' => 'SchoolController@update',
        'before' => 'admin'
    ])->where('id', '[0-9]+');

    // Delete a school
    Route::get('/delete/{id}', [
        'as'   => 'school.delete',
        'uses' => 'SchoolController@destroy',
        'before' => 'admin'
    ])->where('id', '[0-9]+');
});


Route::group(['prefix' => 'profile'], function () {
    /*
      // Show list of users for a school
      Route::get('/', [
          'as' => 'user.index',
          'uses' => 'UserController@index'
      ]);
  */

    // Log user out TODO: post?
    Route::get('/logout', [
        'as'   => 'user.logout',
        'uses' => 'UserController@logout'
    ]);

    // Show the view to edit a user
    Route::get('/{id?}', [
        'as' => 'user.edit',
        'uses' => 'UserController@editUser'
    ])->where('id', '[0-9]+');

    // Add user to group
    Route::post('/{id}/roles', [
        'as' => 'user.addAdminRole',
        'uses' => 'UserController@promoteUserAdmin'
    ])->where('id', '[0-9]+');

    // Remover a user from group
    Route::delete('/{id}/roles', [
        'as' => 'user.removeAdminRole',
        'uses' => 'UserController@demoteUserAdmin'
    ])->where('id', '[0-9]+');

    // Update a user
    Route::post('/{id}', [
        'as'   => 'user.update',
        'uses' => 'UserController@updateUser'
    ])->where('id', '[0-9]+');

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

    // Authenticate user
    Route::post('/auth', [
        'as' => 'user.auth',
        'uses' => 'UserController@auth'
    ]);

    // Destroy a user
    Route::delete('/{id}', [
        'as'   => 'user.removeUserFromSchool',
        'uses' => 'UserController@removeUserFromSchool'
    ])->where('id', '[0-9]+');

    // GET: Reset the user's password with a hash received by mail
    Route::get('/reset/{hash}', [
        'as' => 'user.resetPassword',
        'uses' => 'UserController@resetPassword',
    ])->where([
        'hash' => '[a-zA-Z0-9]+'
    ]);

    // POST: send an email with the reset link
    Route::any('/sendReset/{mail}', [
        'as' => 'user.sendResetLink',
        'uses' => 'UserController@sendResetLink'
    ]);

    // POST: Reset the user's password
    // GET: Reset the user's password
    Route::any('/reset/{hash}', [
        'as' => 'user.resetPassword',
        'uses' => 'UserController@resetPassword',
    ])->where([
        'hash' => '[a-zA-Z0-9]+'
    ]);

});


/***
 * Manages all the calendar/event routes
 */
Route::group(['prefix' => 'calendar'], function ()
{
    // Home
    Route::get('/', [
        'as'   => 'calendar.index',
        'uses' => 'CalendarViewController@index'
    ]);

    // Shows creation form for events
    Route::get('/event/create', [
        'as'   => 'event.create',
        'uses' => 'CalendarViewController@create'
    ]);

    // Stores events
    Route::post('/event/create', [
        'as'   => 'event.store',
        'uses' => 'CalendarViewController@store'
    ]);

    // Shows creation form for events
    Route::get('/event/edit/{id}', [
        'as'   => 'event.edit',
        'uses' => 'CalendarViewController@edit'
    ])->where('id', '[0-9]+');

    // Stores events
    Route::post('/event/edit/{id}', [
        'as'   => 'event.update',
        'uses' => 'CalendarViewController@update'
    ])->where('id', '[0-9]+');

    // Deletes the event with the given ID
    Route::get('/event/delete/{id}', [
        'as'   => 'event.delete',
        'uses' => 'CalendarViewController@destroy'
    ])->where('id', '[0-9]+');

    // Returns all events for the users school
    Route::get('/api/events', [
        'as'   => 'calendar.events',
        'uses' => 'ApiController@events'
    ]);

});

/***
 * Manages all the group routes
 */
Route::group(['prefix' => 'group'], function()
{
    // Index, lists all groups
    Route::get('/', [
        'as'   => 'calendarManagement.index',
        'uses' => 'CalendarController@index'
    ]);

    // Edit a calendar
    Route::get('/{id}', [
        'as'   => 'calendarManagement.edit',
        'uses' => 'CalendarController@edit'
    ])->where('id', '[0-9]+');

    // Create a new group
    Route::get('/create', [
        'as'   => 'calendarManagement.create',
        'uses' => 'CalendarController@create'
    ]);

    // Store a new group
    Route::post('/create', [
        'as'   => 'calendarManagement.store',
        'uses' => 'CalendarController@store'
    ]);

    // Update a group
    Route::post('/edit/{id}', [
        'as'   => 'calendarManagement.update',
        'uses' => 'CalendarController@update'
    ])->where('id', '[0-9]+');

    // Destroy a group
    Route::get('/delete/{id}', [
        'as'   => 'calendarManagement.delete',
        'uses' => 'CalendarController@destroy'
    ])->where('id', '[0-9]+');
});

/**
 * iCal routes and pdf-routes
 * returns iCal, .ics file or .pdf file
 */
Route::group(['prefix' => 'export'], function()
{
    // iCal Export route for a certain class
    // id = calendar
    Route::get('/{id}/{school}/{class}', [
        'as'   => 'export.group',
        'uses' => 'IcalCalendarController@index'
    ])->where(['id' => '[0-9]+', 'school' => '[0-9A-Za-z_\- ]+', 'class' => '[0-9A-Za-z_\- ]+']);

    // iCal Export route for a single appointment
    // id = appointment
    Route::get('/appointment/find/{id}', [
        'as'   => 'export.single',
        'uses' => 'IcalCalendarController@show'
    ])->where('id', '[0-9]+');

    // PDF Export route for a certain class
    // id = calendar
    Route::get('/pdf/{id}/{school}/{class}', [
        'as'   => 'export.group',
        'uses' => 'PdfCalendarController@index'
    ])->where(['id' => '[0-9]+', 'school' => '[0-9a-z_\-]+', 'class' => '[0-9a-z_\-]+']);

    // PDF Export route for a single appointment
    // id = appointment
    Route::get('/appointment/pdf/{id}', [
        'as'   => 'export.singlepdf',
        'uses' => 'PdfCalendarController@show'
    ])->where('id', '[0-9]+');
});