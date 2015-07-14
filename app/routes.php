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


Route::group(['prefix' => 'api/1'], function () {


    // Returns all events for the users organisation
    Route::get('/orgs', [
        'as' => 'api.orgs',
        'uses' => 'ApiController@orgs'
    ]);

    // Returns all events for the users organisation
    Route::get('/orgs/{id}/events', [
        'as' => 'api.events',
        'uses' => 'ApiController@orgEvents'
    ]);

    // Returns all events for the users organisation
    Route::post('/orgs/{id}/events/', [
        'as' => 'api.events',
        'uses' => 'ApiController@storeEvent'
    ]);

    // Returns all events for the users organisation
    Route::post('/orgs/{id}/events/{event_id}', [
        'as' => 'api.events',
        'uses' => 'ApiController@updateEvent'
    ]);

    // Returns all events for the users organisation
    Route::get('/orgs/{id}/users', [
        'as' => 'api.orgUsers',
        'uses' => 'ApiController@orgUsers'
    ]);

    // Returns all events for the users organisation
    Route::get('/orgs/{id}/calendars', [
        'as' => 'api.orgCalendars',
        'uses' => 'ApiController@orgCalendars'
    ]);

    // Returns all events for the users organisation
    Route::get('/calendars/{id}', [
        'as' => 'api.orgCalendars',
        'uses' => 'ApiController@calendarEvents'
    ]);

    /**
     * Returns all events for the users organisation
     * @deprecated
     */
    Route::get('/user/events', [
        'as' => 'api.allUserEvents',
        'uses' => 'ApiController@allUserEvents'
    ]);


    // TODO: post API
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

    // Deletes the event with the given ID
    Route::get('/event/delete/{id}', [
        'as'   => 'event.delete',
        'uses' => 'CalendarViewController@destroy'
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

Route::group(['prefix' => 'admin'], function () {
    // Index, lists all groups
    Route::get('/', [
        'as' => 'admin.index',
        function () {
            // TODO: implement
        }
    ]);

});

Route::pattern('org_slug', '[A-Za-z0-9\-]+');
/**
 * All organisation pages (view, edit, ...)
 */
Route::group(['prefix' => '{org_slug}'], function () {
    // show the calendar for the organisation with the given slug
    Route::get('/', [
        'as' => 'orgs.index',
        'uses' => 'CalendarViewController@index',
    ]);

    Route::get('/dashboard', [
        'as' => 'orgs.index',
        'uses' => 'SchoolController@dashboard',
    ]);

    // Show the admin interface to manage users
    Route::get('/users', [
        'as' => 'orgs.users',
        function () {
            return View::make('admin.users');
        }
    ]);


    // Show the admin interface to manage calendars
    Route::get('/calendars', [
        'as' => 'orgs.calendars',
        function () {
            return View::make('admin.calendars');
        }
    ]);

    // Create a new school
    Route::post('/register', [
        'as' => 'school.store',
        'uses' => 'SchoolController@store'
    ]);

    // Update a school
    Route::post('/edit', [
        'as' => 'school.update',
        'uses' => 'SchoolController@update',
        'before' => 'admin'
    ])->where('id', '[0-9]+');

    // Delete a school
    Route::delete('/', [
        'as' => 'school.delete',
        'uses' => 'SchoolController@destroy',
        'before' => 'admin'
    ]);
});

