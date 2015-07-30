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
                'Voornaam' => Input::get('user-firstname'),
                'Achternaam' => Input::get('user-lastname'),
                'Schoolnaam' => Input::get('school-name'),
                'E-mail adres' => Input::get('user-email'),
                'Stad' => Input::get('school-city'),
                'Wachtwoord' => Input::get('user-password'),
                'Wachtwoord_confirmation' => Input::get('user-password-confirm'),
            ],
            [
                'Voornaam' => 'required',
                'Achternaam' => 'required',
                'Schoolnaam' => 'required|unique:schools,name',
                'Stad' => 'required',
                'E-mail adres' => 'required|email|unique:users,email',
                'Wachtwoord' => 'required|min:8|confirmed',
            ]
        );

        // If validator fails, go back and show errors
        if ($validator->fails()) {
            $validator->getMessageBag()->add('errorMessage',
                'School kon niet geregistreerd worden. Corrigeer de fouten en probeer opnieuw.');

            return Redirect::route('school.register')->withInput()
                ->withErrors($validator);

        }
        // If there are no errors, prepare a new School object to be inserted in the database
        $school = new School();
        $school->name = e(Input::get("school-name"));
        $school->city = e(Input::get("school-city"));
        $school->slug = preg_replace('[^a-zA-Z0-9\-]', '-', e(Input::get("school-name")));
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
            return Redirect::route('landing')->withErrors("Je moet ingelogd zijn om van deze functie gebruikt te maken!");
        }

        $user = Sentry::getUser();
        $school = SchoolController::getSchoolBySlug($slug);

        // Check if user is superAdmin (only they can edit schools)
        if ($user->hasAccess('admin') && $school->id == $user->school_id) {
            return View::make('admin.dashboard')->with('org', $school);
        } else {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect')->withErrors("Je moet ingelogd zijn om van deze functie gebruikt te maken!");
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
        
        if (Input::has("name")) {
            $school->name = e(Input::get("name"));
        }
        if (Input::has("city")) {
            $school->city = e(Input::get("city"));
        }
        if (Input::has("opening")) {
            $school->opening = e(Input::get("opening"));
        }
        $school->save();

        return Redirect::route('school.index');


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
        return View::make('user.register');
    }
}
