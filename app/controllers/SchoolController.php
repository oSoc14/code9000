<?php

/**
 * Class SchoolController
 * This controller handles the CRUD of schools, and associated default calendars that are generated alongside of a school.
 */
class SchoolController extends \BaseController
{

    // TODO: Allow school to update their own information

    protected $layout = 'layout.master';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Sentry::getUser();
        // If user is logged in, redirect to calendar index
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        // Check if user is a superAdmin (other users are not allowed on this page)
        if ($user->hasAccess('superadmin')) {
            $schools = School::get();

            return View::make('school.index')->with('schools', $schools);
        } else {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }

    }

    /**
     * Store a newly created school in storage.
     * Create default calendars.
     * Store new user (schooladmin) as well.
     *
     * @return Response
     */

    // TODO: Get rid of short (reoccuring)
    public function store()
    {
        // If user is logged in, redirect to calendar index
        if (!Sentry::check()) {
            return Redirect::route('calendar.index');
        }

        // Validation rules for input fields
        $validator = Validator::make(
            [
                'per-name' => Input::get('per-name'),
                'per-surname' => Input::get('per-surname'),
                'name' => Input::get('sname'),
                'email' => Input::get('semail'),
                'city' => Input::get('city'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'tos' => Input::get('tos'),
                'honey' => 'honeypot',
                'honey_time' => 'required|honeytime:5'
            ],
            [
                'per-name' => 'required',
                'per-surname' => 'required',
                'name' => 'required|unique:schools,name',
                'city' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'tos' => 'required'
            ]
        );

        // If validator fails, go back and show errors
        if ($validator->fails()) {
            $validator->getMessageBag()->add('schoolerror', 'Failed to make a school');

            return Redirect::route('landing')->withInput()
                ->withErrors($validator);

        }
        // If there are no errors, prepare a new School object to be inserted in the database
        $school = new School();
        $nn = self::clean(e(Input::get("sname")));
        $school->name = $nn;
        $school->city = e(Input::get("city"));
        $school->save();

        // Create the default calendars "global" and "admin"
        // TODO: create new "calendars" instead of the calendars
        // TODO: add this user to the people who can edit the newly created calendars

        // Store the newly created user along with the school
        $user = Sentry::createUser(
            [
                'email' => e(Input::get("semail")),
                'password' => Input::get("password"),
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => e(Input::get("per-name")),
                'last_name' => e(Input::get("per-surname")),
            ]
        );

        // make sure the roles exist
        UserController::checkCreateRoles();
        // Find the role using the calendar id

        $adminRole = Sentry::findGroupByName('admin');

        // Assign the calendar to the user
        $user->addGroup($adminRole);

        $calendar = new Calendar();
        $calendar->name = "global";
        $calendar->description = "events for everyone";
        $calendar->school_id = $school->id;

        $calendar->save();

        // link to global calendar
        $user->calendars()->attach($calendar);

        // Add the user to the admin calendar
        // $user->addcalendar($calendar);

        // Log the user in
        Sentry::login($user, false);

        return Redirect::route('calendar.index');


    }


    /**
     * Display the specified school.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        // If user is logged in, redirect to calendar index
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Check if user is a superAdmin (only he can see this page)
        if ($user->hasAccess('superadmin')) {
            $school = School::find($id);
            $school->load("groups");

            return View::make('school.detail')->with('school', $school);
        } else {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Check if user is superAdmin (only they can edit schools)
        if ($user->hasAnyAccess(['school'])) {
            $school = School::find($id);

            return View::make('school.edit')->with('school', $school);
        } else {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }

    }


    /**
     * Update the specified school in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Check if user is superAdmin (only they can update schools)
        if (!$user->hasAccess('superadmin')) { // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }

        $school = School::find($id);

        $validator = Validator::make(
            [
                'name' => e(Input::get('name')),
                'city' => e(Input::get('city')),
            ],
            [
                'name' => 'required',
                'city' => 'required',
            ]
        );

        // If validator fails, go back and show errors
        if ($validator->fails()) {
            return Redirect::route('school.edit', $id)
                ->withInput()
                ->withErrors($validator);
        } else {
            // Clean up inputted school name
            $nn = self::clean(Input::get("name"));
            // Select the first calendar of the school, if school name changes (should be the global calendar)
            if ($nn != $school->name) {
                $gg = $school->calendars->first();

                $gg->name = $school->name; // TODO: check, is this correct?
                $gg->save();
            }

            $school->name = $nn;
            $school->city = e(Input::get("city"));
            $school->opening = e(Input::get("opening"));
            $school->save();

            return Redirect::route('school.index');
        }


    }


    /**
     * Remove the specified Scgiik from storage.
     *
     * @param  int $id the school ID
     * @return Response
     */

    // TODO: Authenticate in route?
    public function destroy($id)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Check if user is superAdmin (only they can remove schools)
        if (!$user->hasAccess('superadmin')) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }

        $school = School::find($id);
        $school->delete();

        return Redirect::route('school.index');
    }

    /**
     * Remove special characters from a string
     * @param $string
     * @return mixed
     */
    function clean($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    /**
     * Get all admins for a school
     * @param $school_id the ID of the school
     * @return array the admins
     */
    public static function getSchoolAdmins($school_id)
    {
        $adminrole = Sentry::findGroupByName('admin'); // all admins
        $users = Sentry::findAllUsersInGroup($adminrole);

        // we got all admins, but we only need the admins for this school
        $filtered = array();
        foreach ($users as $usr) {
            if ($users->school_id == $school_id) {
                array_push($filtered, $usr);
            }
        }

        return $filtered;
    }


}
