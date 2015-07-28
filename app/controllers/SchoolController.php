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
            return Redirect::route('calendar.redirect');
        }

    }

    /**
     * Store a newly created school in storage.
     * Create default calendars.
     * Store new user (schooladmin) as well.
     * @return Response
     */

    // TODO: Get rid of short (reoccuring)
    public function store()
    {
        // Validation rules for input fields
        $validator = Validator::make(
            [
                'user-firstname' => Input::get('user-firstname'),
                'user-lastname' => Input::get('user-lastname'),
                'school-name' => Input::get('school-name'),
                'user-email' => Input::get('user-email'),
                'school-city' => Input::get('school-city'),
                'user-password' => Input::get('user-password'),
                'user-password_confirmation' => Input::get('user-password-confirm'),
            ],
            [
                'user-firstname' => 'required',
                'user-lastname' => 'required',
                'school-name' => 'required|unique:schools,name',
                'school-city' => 'required',
                'user-email' => 'required|email|unique:users,email',
                'user-password' => 'required|min:8|confirmed',
            ]
        );

        // If validator fails, go back and show errors
        if ($validator->fails()) {
            $validator->getMessageBag()->add('errorMessage', 'Failed to make a school');

            return Redirect::route('school.register')->withInput()
                ->withErrors($validator);

        }
        // If there are no errors, prepare a new School object to be inserted in the database
        $school = new School();
        $nn = self::clean(e(Input::get("school-name")));
        $school->name = $nn;
        $school->city = e(Input::get("school-city"));
        $school->slug = preg_replace('[^a-zA-Z0-9\-]', '', $nn);
        $school->save();

        // Create the default calendars "global" and "admin"
        // TODO: create new "calendars" instead of the calendars

        // Store the newly created user along with the school
        $user = Sentry::createUser(
            [
                'email' => e(Input::get("user-email")),
                'password' => Input::get("user-password"),
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => e(Input::get("user-firstname")),
                'last_name' => e(Input::get("user-lastname")),
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

        return Redirect::route('calendar.redirect');
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug the slug of the school for which we want to show the dashboard
     * @return Response
     */
    public function dashboard($slug)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing')->withErrors("You have to be logged in to use this function!");
        }

        $user = Sentry::getUser();
        $school = SchoolController::getSchoolBySlug($slug);

        // Check if user is superAdmin (only they can edit schools)
        if ($user->hasAccess('admin') && $school->id == $user->school_id) {
            return View::make('admin.dashboard')->with('org', $school);
        } else {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect')->withErrors("You have to be logged in as admin to use this function!");
        }

    }

    /**
     * Update the specified school in storage.
     *
     * @param  string $slug the slug of the school to update
     * @return Response
     */
    public function updateSchoolBySlug($slug)
    {
        $this->updateSchool(SchoolController::getSchoolBySlug($slug));
    }

    /**
     * Update the specified school in storage.
     *
     * @param  int $id the Id of the school to update
     * @return Response
     */
    public function updateSchoolById($id)
    {
        $this->updateSchool(School::find($id));
    }

    /**
     * Update the specified school in storage.
     *
     * @param  School $school the school to update
     * @return Response
     */
    public function updateSchool($school)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Check if user is superAdmin (only they can update schools)
        if (!$user->hasAccess('admin') || $user->school_id != $school->id) { // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect');
        }


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
            return Redirect::route('school.edit', $school->slug)
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
     * Remove the specified school from storage.
     *
     * @param  string $slug the school slug
     * @return Response
     */
    public function destroySchoolBySlug($slug)
    {
        $this->destroySchool(SchoolController::getSchoolBySlug($slug));
    }

    /**
     * Remove the specified school from storage.
     *
     * @param  int $id the school ID
     * @return Response
     */
    public function destroySchoolById($id)
    {
        $this->destroySchool(School::find($id));
    }

    // TODO: Authenticate in route?
    /**
     * Destroy a school after checking for permissions
     * @param $school \School the school to destroy
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroySchool($school)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Check if user is superAdmin (only they can remove schools)
        if (!$user->hasAccess('superadmin')) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect');
        }

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
     * @param $school_id int the ID of the school
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

    /**
     * @param $school_slug
     * @return \School |static
     */
    public static function getSchoolBySlug($school_slug)
    {
        return School::where('slug', $school_slug)->firstOrFail();
    }

    public static function showRegisterForm()
    {
        return View::make('register');
    }
}
