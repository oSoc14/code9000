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

if (Session::has('lang')) {
    //Set the language
    App::setLocale(Session::get('lang'));
}

Route::get('/', [
    'as' => 'landing',
    'uses' => 'HomeController@showWelcome'
]);


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
        'as' => 'user.logout',
        'before' => 'guest',
        'uses' => 'UserController@logout'
    ]);



    // Show the view to edit a user
    Route::get('/{id?}', [
        'as' => 'user.edit',
        'before' => 'auth',
        'uses' => 'UserController@editUser'
    ])->where('id', '[0-9]+');

    // Register as a new user
    Route::post('/register', [
        'as' => 'user.register',
        'before' => 'guest',
        'uses' => 'UserController@store'
    ]);

    // Authenticate user
    Route::post('/auth', [
        'as' => 'user.auth',
        'uses' => 'UserController@auth'
    ]);

    // Destroy a user
    Route::delete('/{id}', [
        'as' => 'user.removeUserFromSchool',
        'before' => 'auth',
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

    /**
     *  ! important
     *  Don't filter API calls for auth, admin, ...
     *  API methods have their own error responses and should not return empty 401 errors
     */

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
    Route::post('/events/', [
        'as' => 'api.events',
        'uses' => 'ApiController@handleAppointment'
    ]);

    // Returns all events for the users organisation
    Route::delete('/events/', [
        'as' => 'api.events',
        'uses' => 'ApiController@destroyAppointment'
    ]);


    Route::get('/calendars/', [
        'as' => 'api.currentOrgCalendars',
        'uses' => 'ApiController@orgCalendars'
    ]);

    // Returns all events for a calendar
    Route::get('/calendars/{id}', [
        'as' => 'api.orgCalendarWithEvents',
        'uses' => 'ApiController@calendarWithEvents'
    ]);

    // Returns all events for a calendar
    Route::get('/calendars/{id}/events', [
        'as' => 'api.orgCalendarEvents',
        'uses' => 'ApiController@calendarEvents'
    ]);

    // Returns all events for a calendar
    Route::post('/calendars/', [
        'as' => 'api.handleCalendar',
        'uses' => 'ApiController@handleCalendar'
    ]);

    // Returns all events for a calendar
    Route::delete('/calendars/', [
        'as' => 'api.deleteCalendar',
        'uses' => 'ApiController@destroyCalendar'
    ]);


    Route::get('/users/', [
        'as' => 'api.currentOrgUsers',
        'uses' => 'ApiController@orgUsers'
    ]);

    // TODO: get user?

    // Update a user
    Route::post('/users/{id}', [
        'as' => 'user.update',
        'uses' => 'UserApiController@updateUser'
    ])->where('id', '[0-9]+');

    // Update a user
    Route::delete('/users/{id}', [
        'as' => 'user.update',
        'uses' => 'UserApiController@deleteUser'
    ])->where('id', '[0-9]+');

    // Create a new user (backoffice side)
    Route::post('/users/', [
        'as' => 'user.create',
        'uses' => 'UserApiController@createUser'
    ]);

    // Add user to group
    Route::post('/users/{id}/roles', [
        'as' => 'user.addAdminRole',
        'uses' => 'UserApiController@addAdminRole'
    ])->where('id', '[0-9]+');

    // Remover a user from group
    Route::delete('/users/{id}/roles', [
        'as' => 'user.removeAdminRole',
        'uses' => 'UserApiController@removeAdminRole'
    ])->where('id', '[0-9]+');


    // Add user to group
    Route::post('/users/link', [
        'as' => 'user.addToCalendar',
        'uses' => 'UserApiController@addUserToCalendar'
    ]);

    // Remover a user from group
    Route::delete('/users/link', [
        'as' => 'user.removeFromCalendar',
        'uses' => 'UserApiController@removeUserFromCalendar'
    ]);
});


// Home
Route::get('/calendar', [
    'as' => 'calendar.redirect',
    'uses' => 'CalendarViewController@goToCalendar'
]);

Route::get('/login', [
    'as' => 'user.login',
    function () {
        return View::make('user.login');
    }
]);

// Create a new school
Route::get('/register', [
    'as' => 'school.register',
    'uses' => 'SchoolController@showRegisterForm'
]);

// Create a new school
Route::post('/register', [
    'as' => 'school.store',
    'uses' => 'SchoolController@store'
]);


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
        'as' => 'admin.dashboard',
        'before' => 'admin',
        'uses' => 'SchoolController@dashboard',
    ]);

    // Show the admin interface to manage users
    Route::get('/users', [
        'as' => 'admin.users',
        'before' => 'admin',
        function ($slug) {
            return View::make('admin.users')->with('org', SchoolController::getSchoolBySlug($slug));
        }
    ]);

    // Show the admin interface to manage calendars
    Route::get('/calendars', [
        'as' => 'admin.calendars',
        'before' => 'admin',
        function ($slug) {
            return View::make('admin.calendars')->with('org', SchoolController::getSchoolBySlug($slug));
        }
    ]);

    // e.g. educal.dev/school/calendar1/calendar2.ics to compile an ics file for calendar 1 and 2
    Route::get('/{calendar_slug}.ics', [
        'as' => 'export.ics',
        'uses' => 'IcalCalendarController@index'
    ])->where('calendar_slug', '[0-9A-Za-z_\-+ ]+');

    Route::get('/{calendar_slug}', [
        'as' => 'export.index',
        function ($org, $calendar_slug) {
            return View::make('calendar.export')->with(['org' => $org, 'calendars' => $calendar_slug]);
        }
    ])->where('calendar_slug', '[0-9A-Za-z_\-+ ]+');

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

