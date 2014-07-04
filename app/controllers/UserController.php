<?php

class UserController extends \BaseController {

    protected $layout = 'layout.master';

    /**
     * Get all users for a school, where they can be activated as well by the administration
     * Show these on view
     * TODO: PERMISSIONS List users by school
     */
    public function index()
    {
        $user = Sentry::getUser();
        // If user is logged in, get school_id, find respective users
        if(Sentry::check()) {
            $schoolId = $user->school_id;
            // Get all users with this school_id, except for the logged in user
            $users = User::where('school_id', $schoolId)
                ->where('id','<>',$user->id)
                ->get();
            $school = School::where('id', $schoolId)->first();
            $schoolName = $school->name;

            return View::make('user.index')
                ->with('users', $users)
                ->with('schoolName', $schoolName);
        }
        // If no permissions, redirect to calendar index
        return Redirect::route('calendar.index');
    }

    /**
     * Get a single user based on his ID, the admin can edit certain settings in this view
     * TODO: PERMISSIONS List users by schoolsingle user
     * TODO: Activate user possibility
     */
    public function show($id)
    {
        $user = Sentry::findUserByID($id);
        $user->load('school');
        $user->load('groups');

        return View::make('user.show')
            ->with('user', $user);
    }

    public function auth()
    {
        try
        {
            // Login credentials
            $credentials = array(
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
            );

            // Authenticate the user
            $user = Sentry::authenticate($credentials, false);

            // If "remember me" is checked, make cookie, else don't make cookie
            if(Input::get('remember')) {
                Sentry::loginAndRemember($user);
            } else {
                Sentry::login($user);
            }

            // Redirect to logged in page
            return Redirect::route('calendar.index');
        }
        // Error handling
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            // No email input
            $errorMessage = 'Login field is required.';
        }
        catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            // No password input
            $errorMessage = 'Password field is required.';
        }
        catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
        {
            // Wrong password input
            $errorMessage = 'Wrong password. Try again.';
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $errorMessage = 'User was not found.';
        }
        catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            $errorMessage = 'User is not activated.';
        }
        // The following is only required if the throttling is enabled
        catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
        {
            $errorMessage = 'User is suspended.';
        }
        catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            $errorMessage = 'User is banned.';
        }
        // If there is an errormessage, return to login page
        // With errorMessage
        if($errorMessage) {
            return Redirect::route('landing')
            ->withInput()
            ->with('errorMessage', $errorMessage);
        }
    }

    public function store()
    {
        $validator = Validator::make(
            array(
                'name' => Input::get('name'),
                'surname' => Input::get('surname'),
                'email' => Input::get('email'),
                'school' => Input::get('school'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'tos' => Input::get('tos')
            ),
            array(
                'name' => 'required',
                'surname' => 'required',
                'school' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'tos' => 'required'
            )
        );
        if ($validator->fails())
        {
            return Redirect::route('landing')->withInput()->withErrors($validator);
        }
        else{
            Sentry::createUser(array(
                'email'    => Input::get('email'),
                'password' => Input::get('password'),
                'activated' => false,
                'school_id' => Input::get('school'),
                'first_name' => Input::get('name'),
                'last_name' => Input::get('surname'),
            ));
            return Redirect::route('landing');
        }
    }

    /**
     * Remove a user from a school
     * @param $id = userID
     * TODO: PERMISSIONS remove user from school
     */
    public function removeUserFromSchool($id)
    {
        try
        {
            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Delete the user
            $user->delete();

            // Return to the previous page
            return Redirect::back();
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $error = 'User was not found.';
            // Return to the previous page
            return Redirect::back()->with('error', $error);
        }

    }

    /**
     * Activate a user so that he gets access to the school (as a teacher for example)
     * @param $id = userID
     * TODO: Try catch block
     */
    public function activateUser($id)
    {
        // Find the user using the user id
        $user = Sentry::findUserById($id);
        $activationCode = $user->getActivationCode();
        // Attempt to activate the user
        if($user->activated == 0) {
            $user->attemptActivation($activationCode);
            return $user;
        } else {
            // Deactivate user
            $user->activated = 0;
            $user->save();
            return $user;
        }
    }

    // Log out function
    public function logout()
    {
        // If user is logged in, then log out the user
        if(Sentry::check()) {
            Sentry::logout();
        }
        // Redirect to root
        return Redirect::route('landing');
    }

    public function addToGroup($group_id) {
        $user = Sentry::findUserById(Input::get('user'));

        // Find the group using the group id
        $group = Sentry::findGroupById($group_id);

        $user->addGroup($group);

        return Redirect::back();
    }
}