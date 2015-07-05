<?php

/**
 * Class SchoolController
 * This controller handles the CRUD of schools, and associated default groups that are generated alongside of a school.
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
        if (Sentry::check()) {

            // Check if user is a superAdmin (other users are not allowed on this page)
            if ($user->hasAccess('school')) {
                $schools = School::get();
                return View::make('school.index')->with('schools', $schools);
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
    }

    /**
     * Store a newly created school in storage.
     * Create default groups.
     * Store new user (schooladmin) as well.
     *
     * @return Response
     */

    // TODO: Get rid of short (reoccuring)
    public function store()
    {
        // If user is logged in, redirect to calendar index
        if (!Sentry::check()) {

            // Validation rules for input fields
            $validator = Validator::make(
                [
                    'per-name'              => Input::get('per-name'),
                    'per-surname'           => Input::get('per-surname'),
                    'name'                  => Input::get('sname'),
                    'email'                 => Input::get('semail'),
                    'city'                  => Input::get('city'),
                    'password'              => Input::get('password'),
                    'password_confirmation' => Input::get('password_confirmation'),
                    'tos'                   => Input::get('tos'),
                    'honey'                 => 'honeypot',
                    'honey_time'            => 'required|honeytime:5'
                ],
                [
                    'per-name'      => 'required',
                    'per-surname'   => 'required',
                    'name'          => 'required|unique:schools,name',
                    'city'          => 'required',
                    'email'         => 'required|email|unique:users,email',
                    'password'      => 'required|min:8|confirmed',
                    'tos'           => 'required'
                ]
            );

            // If validator fails, go back and show errors
            if ($validator->fails()) {
                $validator->getMessageBag()->add('schoolerror', 'Failed to make a school');

                return Redirect::route('landing')->withInput()
                    ->withErrors($validator);

            } else {
                // If there are no errors, prepare a new School object to be inserted in the database
                $school       = new School();
                $nn           = self::clean(e(Input::get("sname")));
                $school->name = $nn;
                $school->city = e(Input::get("city"));
                $school->save();

                // Create the default groups "global" and "admin"
                Sentry::createGroup(
                    [
                        'name'        => $school->name . '__' . $school->id,
                        'permissions' => [
                            'school'  => 0,
                            'user'    => 0,
                            'group'   => 0,
                            'event'   => 1,
                        ],
                        'school_id'   => $school->id,
                    ]
                );

                $group = Sentry::createGroup(
                    [
                        'name'        => 'Administratie__' . $school->id,
                        'permissions' => [
                            'school'  => 0,
                            'admin'   => 1,
                            'user'    => 1,
                            'group'   => 1,
                            'event'   => 1,
                        ],
                        'school_id'   => $school->id,
                    ]
                );

                // Store the newly created user along with the school
                $user = Sentry::createUser(
                    [
                        'email'     => e(Input::get("semail")),
                        'password'  => Input::get("password"),
                        'activated' => true,
                        'school_id' => $school->id,
                        'first_name'=> e(Input::get("per-name")),
                        'last_name' => e(Input::get("per-surname")),
                    ]
                );

                // Add the user to the admin group
                $user->addGroup($group);

                // Log the user in
                Sentry::login($user, false);

                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('calendar.index');
        }
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
        if (Sentry::check()) {
            $user = Sentry::getUser();

            // Check if user is a superAdmin (only he can see this page)
            if ($user->hasAccess('school')) {
                $school = School::find($id);
                $school->load("groups");
                return View::make('school.detail')->with('school', $school);
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
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
        if (Sentry::check()) {

            $user = Sentry::getUser();

            // Check if user is superAdmin (only they can edit schools)
            if ($user->hasAnyAccess(['school'])) {
                $school = School::find($id);
                return View::make('school.edit')->with('school', $school);
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
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
        if (Sentry::check()) {

            $user = Sentry::getUser();

            // Check if user is superAdmin (only they can update schools)
            if ($user->hasAccess('school')) {
                $school = School::find($id);

                $validator = Validator::make(
                    [
                        'name'     => e(Input::get('name')),
                        'city'     => e(Input::get('city')),
                    ],
                    [
                        'name'     => 'required',
                        'city'     => 'required',
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
                    // Select the first group of the school, if school name changes (should be the global group)
                    if($nn != $school->name) {
                        $gg = $school->groups->first();

                        $gg->name = $nn . '__' . $school->id;
                        $gg->save();
                    }

                    $school->name    = $nn;
                    $school->city    = e(Input::get("city"));
                    $school->opening = e(Input::get("opening"));
                    $school->save();

                    return Redirect::route('school.index');
                }
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */

    // TODO: Authenticate in route?
    public function destroy($id)
    {
        if (Sentry::check()) {

            $user = Sentry::getUser();

            // Check if user is superAdmin (only they can remove schools)
            if ($user->hasAccess('school')) {
                $school = School::find($id);
                $school->delete();

                return Redirect::route('school.index');

            } else {
                // If no permissions, redirect the user to the calendar index page
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
    }

    function clean($string) {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

}
