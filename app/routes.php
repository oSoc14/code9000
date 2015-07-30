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

Route::get('/login', [
    'as' => 'user.login',
    function () {
        return View::make('user.login');
    }
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

    // Authenticate user
    Route::post('/auth', [
        'as' => 'user.auth',
        'uses' => 'UserController@auth'
    ]);

    // Update a user
    Route::post('/{id}', [
        'as' => 'user.update',
        'before' => 'auth',
        'uses' => 'UserController@updateUser'
    ])->where('id', '[0-9]+');

    // POST: send an email with the reset link
    Route::post('/sendReset', [
        'as' => 'user.sendResetLink',
        'uses' => 'UserController@sendResetLink'
    ]);

    Route::get('/reset/', [
        'as' => 'user.requestResetMail',
        function () {
            return View::make('user.passwordforgotten');
        }
    ]);

    // POST: Reset the user's password: handle form
    // GET: Reset the user's password with hash received by mail
    Route::any('/reset/{hash}', [
        'as' => 'user.resetPassword',
        'uses' => 'UserController@resetPassword',
    ])->where([
        'hash' => '[a-zA-Z0-9]+'
    ]);

});

/**
 * Api v1
 * Used to show the calendar with AJAX
 */
Route::group(['prefix' => 'api/1'], function () {

    /**
     *  ! important
     *  Don't filter API calls for auth, admin, ...
     *  API methods have their own error responses and should not return empty 401 errors
     */

    /**
     * Get all organisations
     */
    Route::get('/orgs', [
        'as' => 'api.org.list',
        'uses' => 'ApiController@orgs'
    ]);

    /**
     * Get all events for an organisation
     */
    Route::get('/orgs/{id}/events', [
        'as' => 'api.org.events',
        'uses' => 'ApiController@orgEvents'
    ]);

    /**
     * Get all users for an organisation
     */
    Route::get('/orgs/{id}/users', [
        'as' => 'api.org.users',
        'uses' => 'ApiController@orgUsers'
    ]);

    /**
     * Get all calendars for an organisation
     */
    Route::get('/orgs/{id}/calendars', [
        'as' => 'api.org.calendars',
        'uses' => 'ApiController@orgCalendars'
    ]);


    /**
     * Get all users in an organisation
     */
    Route::get('/users/', [
        'as' => 'api.currentorg.users',
        'uses' => 'ApiController@orgUsers'
    ]);

    /**
     * Handle an event creation/update
     */
    Route::post('/events/', [
        'as' => 'api.event.handle',
        'uses' => 'ApiController@handleAppointment'
    ]);

    /**
     * Delete an event
     */
    Route::delete('/events/', [
        'as' => 'api.event.delete',
        'uses' => 'ApiController@destroyAppointment'
    ]);

    /**
     * Get all calendars for the currently logged in user's organisation
     */
    Route::get('/calendars/', [
        'as' => 'api.currentorg.calendars',
        'uses' => 'ApiController@orgCalendars'
    ]);

    /**
     * Get a calendar, including all events
     * id: calendar id
     */
    Route::get('/calendars/{id}', [
        'as' => 'api.calendar.get',
        'uses' => 'ApiController@calendarWithEvents'
    ]);

    /**
     * Get an array of all events in a calendar
     * id: calendar id
     */
    Route::get('/calendars/{id}/events', [
        'as' => 'api.calendar.events',
        'uses' => 'ApiController@calendarEvents'
    ]);

    /**
     * Handle a calendar create/update
     */
    Route::post('/calendars/', [
        'as' => 'api.calendar.handle',
        'uses' => 'ApiController@handleCalendar'
    ]);

    /**
     * Delete a calendar
     */
    Route::delete('/calendars/', [
        'as' => 'api.calendar.delete',
        'uses' => 'ApiController@destroyCalendar'
    ]);

    /**
     * Check if user is logged in
     */
    Route::get('/users/logged', [
        'as' => 'api.users.status',
        'uses' => 'UserApiController@checkLoginState'
    ]);
    /**
     * Create a new user (done from the backoffice side)
     */
    Route::get('/users/{id}', [
        'as' => 'api.users.getUser',
        'uses' => 'UserApiController@getUser'
    ])->where('id', '[0-9]+');

    /**
     * Get the calendar id's for a specific user
     */
    Route::get('/users/{id}/calendars', [
        'as' => 'api.users.getUserCalendarIds',
        'uses' => 'UserApiController@getUserCalendarIds'
    ])->where('id', '[0-9]+');

    /**
     * Resend an email to reset the password
     */
    Route::post('/users/{id}/mail', [
        'as' => 'api.users.mail',
        'uses' => 'UserApiController@sendPasswordLink'
    ])->where('id', '[0-9]+');

    /**
     * Create a new user (done from the backoffice side)
     */
    Route::post('/users/', [
        'as' => 'api.users.create',
        'uses' => 'UserApiController@createUser'
    ])->where('id', '[0-9]+');

    /**
     * Update an existing user
     * id: the user id
     */
    Route::post('/users/{id}', [
        'as' => 'api.users.update',
        'uses' => 'UserApiController@updateUser'
    ])->where('id', '[0-9]+');

    /**
     * Delete an existing user
     * id: the user id
     */
    Route::delete('/users/{id}', [
        'as' => 'api.users.delete',
        'uses' => 'UserApiController@deleteUser'
    ])->where('id', '[0-9]+');

    /**
     * Promote a user from editor to admin
     * id: the id of the user to promote
     */
    Route::post('/users/{id}/roles', [
        'as' => 'api.users.promote',
        'uses' => 'UserApiController@addAdminRole'
    ])->where('id', '[0-9]+');

    /**
     * Demote a user from admin to editor
     * id: the id of the user to demote
     */
    Route::delete('/users/{id}/roles', [
        'as' => 'api.users.demote',
        'uses' => 'UserApiController@removeAdminRole'
    ])->where('id', '[0-9]+');


    /**
     * Link a user to a calendar
     * Post parameters:
     * id: user id
     * calendar_id: calendar id
     */
    Route::post('/users/link', [
        'as' => 'api.users.link',
        'uses' => 'UserApiController@addUserToCalendar'
    ]);

    /**
     * Unlink a user from a calendar
     * Post parameters:
     * id: user id
     * calendar_id: calendar id
     */
    Route::post('/users/unlink', [
        'as' => 'api.users.unlink',
        'uses' => 'UserApiController@removeUserFromCalendar'
    ]);
});


// Home
Route::get('/calendar', [
    'as' => 'calendar.redirect',
    'uses' => 'CalendarViewController@goToCalendar'
]);

Route::get('/faq', [
    'as' => 'static.faq',
    function () {
        return View::make('faq');
    }
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
            return View::make('calendar.export')->with([
                'org_slug' => $org,
                'calendars' => $calendar_slug,
                'org' => School::getBySlug($org)
            ]);
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

