<?php

/**
 * Class UserController
 * This controller handles the CRUD and authentications of users.
 */
class UserController extends \BaseController
{

    protected $layout = 'layout.master';

    /**
     * Get all users for a school, where they can be activated as well by the administration
     * Show these on view
     *
     * @return mixed
     */
    public function index()
    {
        // If user is logged in, get school_id, find respective users
        if (Sentry::check()) {
            // Get info from the logged in user
            $user = Sentry::getUser();

            // Make an empty users object which will hold all the users from a certain school
            $users = null;

            // Get all schools, and put them in an array with the key-value pair school_id=>school_name
            $schools = School::get();
            $schoolsArray = [];
            foreach ($schools as $school) {
                $schoolsArray[$school->id] = $school->name;
            }

            // Check if user is superAdmin
            if ($user->hasAccess('school')) {
                $users = User::where('id', '<>', $user->id)->get();
                $school = null;

            } elseif ($user->hasAccess('user')) {
                // If user is no superAdmin, display users based on the logged in user's school
                $schoolId = $user->school_id;

                // Get all users with this school_id, except for the logged in user
                $users  = User::where('school_id', $schoolId)
                    ->where('id', '<>', $user->id)
                    ->get();

                $school = School::where('id', $schoolId)->first();

            } else {
                return Redirect::route('calendar.index');
            }

            return View::make('user.index')
                ->with('users', $users)
                ->with('school', $school)
                ->with('schools', $schoolsArray);
        }

        // If no permissions, redirect to calendar index
        return Redirect::route('calendar.index');
    }

    /**
     * Authenticate users
     * TODO: Custom variable error messages (multiple language support)
     * @return mixed
     */
    public function auth()
    {
        try {
            // Login credentials
            $credentials = [
                'email'    => Input::get('lemail'),
                'password' => Input::get('password'),
            ];

            // Authenticate the user
            $user = Sentry::authenticate($credentials, false);

            // If "remember me" is checked, make cookie, else don't make cookie
            if (Input::get('remember')) {
                Sentry::loginAndRemember($user);
            } else {
                Sentry::login($user);
            }

            //Get the users prefered language
            $user = Sentry::getUser();
            if ($user->lang != null && $user->lang != '') {
                Session::put('lang', $user->lang);
            } elseif ($user->school != null) {
                Session::put('lang', $user->school->lang);
            } else {
                Session::put('lang', 'nl');
            }

            //Set the language
            App::setLocale(Session::get('lang'));

            // Redirect to logged in page
            return Redirect::route('calendar.index');

        } // Error handling
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            // No email input
            $errorMessage = 'Login field is required.';
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            // No password input
            $errorMessage = 'Password field is required.';
        } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
            // Wrong password input
            $errorMessage = 'Wrong password. Try again.';
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $errorMessage = 'User was not found.';
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            $errorMessage = 'User is not activated.';
        } // The following is only required if the throttling is enabled
        catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            $errorMessage = 'User is suspended.';
        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            $errorMessage = 'User is banned.';
        }

        // If there is an errormessage, return to login page with errorMessage
        if ($errorMessage) {
            return Redirect::route('landing')
                ->withInput()
                ->with('errorMessage', $errorMessage);
        }
    }

    /**
     * Store a new user in the database
     * @return mixed
     */
    public function store()
    {
        // Define validation rules
        $validator = Validator::make(
            [
                'name'                  => Input::get('name'),
                'surname'               => Input::get('surname'),
                'email'                 => Input::get('email'),
                'school'                => Input::get('school'),
                'password'              => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'tos'                   => Input::get('tos'),
                'honey'                 => 'honeypot',
                'honey_time'            => 'required|honeytime:5'
            ],
            [
                'name'     => 'required',
                'surname'  => 'required',
                'school'   => 'required',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'tos'      => 'required'
            ]
        );

        // If validation fails, return to previous page with errorMessages
        if ($validator->fails()) {
            $validator->getMessageBag()->add('usererror', 'Failed to make a user');

            return Redirect::route('landing')
                ->withInput()
                ->withErrors($validator);

        } else {
            // If there are no errors, create a new user
            Sentry::createUser(
                [
                    'email'      => Input::get('email'),
                    'password'   => Input::get('password'),
                    'activated'  => false,
                    'school_id'  => Input::get('school'),
                    'first_name' => e(Input::get('name')),
                    'last_name'  => e(Input::get('surname')),
                ]
            );

            return Redirect::route('landing');
        }
    }

    /**
     * Creates a new user from the back-office side
     * @return mixed
     */
    public function create()
    {
        if (Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();

            // Permission checks
            if ($user->hasAccess('school') || ($user->hasAccess('user') && $user->school_id == Input::get('school'))) {

                // Validate inputted data
                $validator = Validator::make(
                    [
                        'name'                  => Input::get('name'),
                        'surname'               => Input::get('surname'),
                        'email'                 => Input::get('email'),
                        'password'              => Input::get('password'),
                        'password_confirmation' => Input::get('password_confirmation'),
                    ],
                    [
                        'name'     => 'required',
                        'surname'  => 'required',
                        'email'    => 'required|email|unique:users,email',
                        'password' => 'required|min:8|confirmed',
                    ]
                );

                // If validation fails, return to previous page with errors
                if ($validator->fails()) {
                    $validator->getMessageBag()->add('usererror', 'Failed to make a user');

                    return Redirect::back()
                        ->withInput()
                        ->withErrors($validator);

                } else {
                    // If there are no validation errors, handle data in the correct way
                    // Get schoolId from the input field
                    $schoolId = Input::get('school');

                    // If the superAdmin tries to make another superAdmin, then schoolId = null, because superadmins
                    // don't belong to a school
                    if ($user->hasAccess('school') && Input::get('superAdmin')) {
                        $schoolId = null;
                    }

                    // Create a new user
                    $created = Sentry::createUser(
                        [
                            'email'      => Input::get('email'),
                            'password'   => Input::get('password'),
                            'activated'  => true,
                            'school_id'  => $schoolId,
                            'first_name' => Input::get('name'),
                            'last_name'  => Input::get('surname'),
                        ]
                    );

                    // If a superAdmin was created, then we add him to the 1st group in the database, which is the
                    // superadmin group
                    if ($user->hasAccess('school') && Input::get('superAdmin')) {
                        $group = Sentry::findGroupById(1);
                        $created->addGroup($group);
                    }
                }

                // Return to previous page after everything is done
                return Redirect::route('user.index');
            }
        } else {
            return Redirect::route('landing');
        }
    }


    /**
     * Remove a user from a school
     * @param $id = userID
     */
    public function removeUserFromSchool($id)
    {
        // If user is logged in, check for permissions
        if (Sentry::check()) {
            $user = Sentry::getUser();

            // Permission check
            if ($user->hasAnyAccess(['school', 'admin'])) {
                try {
                    // Find the user using the user id
                    $selectedUser = Sentry::findUserById($id);

                    /**
                     * Check if the selected user is in the admins group,
                     * ->true: check if he is the last person in that group
                     *          -> true: don't allow user to be removed (school needs 1 admin at least)
                     *          -> false: delete user from school
                     * ->false: safe to remove user from school
                     */
                    if ($selectedUser->hasAccess('admin')
                        && ($selectedUser->school_id == $user->school_id || $user->hasAccess('school'))
                    ) {
                        // Get the schoolShort, based on that find the admin group and all users in that group
                        $school = School::find($selectedUser->school_id);
                        $group  = Sentry::findGroupByName($school->short . '_admin');
                        $users  = Sentry::findAllUsersInGroup($group);

                        // If there is more than 1 user in the school_admin group, then the user can be safely removed
                        if (count($users) > 1) {
                            // Delete the user
                            $selectedUser->delete();

                            // Return to the previous page
                            return Redirect::back();

                        } else {
                            // If there is only 1 user (or less), then we can't delete the user
                            $error = "You can't remove this user.";

                            // Return to the previous page
                            return Redirect::route('user.index')->with('error', $error);
                        }
                    } else {
                        // Delete the user
                        $selectedUser->delete();

                        // Return to the previous page
                        return Redirect::route('user.index');
                    }
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    $error = 'User was not found.';

                    // Return to the previous page
                    return Redirect::route('user.index')->with('error', $error);

                }
            } else {

                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {

            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
    }

    /**
     * Activate a user so that he gets access to the school (as a teacher for example)
     * @param $id = userID
     * @return mixed
     */
    public function activateUser($id)
    {
        // If user is logged in, get school_id, find respective users
        if (Sentry::check()) {
            $user = Sentry::getUser();

            // Permission check
            if ($user->hasAnyAccess(['school', 'user'])) {

                try {
                    // Find the user using the user id
                    $selectedUser = Sentry::findUserById($id);

                    // Check if all permission are in order, if ok, just progress through the script, else we handle errors
                    // A user can't deactivate/activate himself, he needs to have "user" permissions, be in the same
                    // school.
                    // Alternatively, a superAdmin can do all
                    if (($user->school_id == $selectedUser->school_id || $user->hasAccess(
                                'school'
                            )) && $selectedUser->id != $user->id
                    ) {
                        // Generate a new activation code for the selected user
                        $activationCode = $selectedUser->getActivationCode();

                        // Check if a user is already activated
                        if ($selectedUser->activated == 0) {
                            // Activate the user if he isn't activated yet
                            $selectedUser->attemptActivation($activationCode);

                            return $selectedUser;

                        } else {
                            // If the user is already active, deactivate user
                            $selectedUser->activated = 0;
                            $selectedUser->save();

                            return $selectedUser;
                        }

                    } else {
                        // Permissions not ok, return to calendar index
                        return Redirect::route('calendar.index');
                    }
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    return Redirect::route('calendar.index');
                }

            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
    }


    /**
     * Edit user settings for a given ID (if permissions allow it), otherwise edit own user settings
     * @param $id = userID
     * @return mixed
     */
    public function editUser($id = null)
    {
        // Check if user is logged in
        if (Sentry::check()) {

            $user = Sentry::getUser();

            // If id is given, find user by that id
            if ($id != null) {

                try {
                    $selectedUser = Sentry::findUserById($id);
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                        return Redirect::route('calendar.index');
                }

            } else {
                // If no id is given, make the id equal to the id of the logged in user (User is trying to edit himself)
                $selectedUser = Sentry::getUser();
                $id           = $selectedUser->id;
            }

            // Check permissions for the user (user has to be either a superAdmin, edit himself, or have user permissions
            // for the same school as the user he is trying to edit
            if ($user->hasAccess('school') || $user->id == $id
                || ($user->hasAccess('user') && $user->school_id == $selectedUser->school_id)) {

                return View::make('user.edit')->with('user', $selectedUser);

            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }

        } else {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
    }

    /**
     * Update userSettings
     * @param $id = userID
     * @return mixed
     */
    public function updateUser($id)
    {
        if (Sentry::check()) {
            // Select users
            $user = Sentry::getUser();

            // Try-catch block for trying to find the selected User (to prevent crashing)
            try {
                $selectedUser = Sentry::findUserById($id);

            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                return Redirect::route('calendar.index');
            }

            // Check if the user that wants to do the update is either the user himself,
            // or another user with the correct permissions (such as the superAdmin, or an administrator from the school)
            if ($user->hasAccess('school') || $user->id == $id || ($user->hasAccess('user')
                    && $user->school_id == $selectedUser->school_id)
            ) {
                // Validate the inputs
                $validator = Validator::make(
                    [
                        'name'                  => Input::get('name'),
                        'surname'               => Input::get('surname'),
                        'email'                 => Input::get('email'),
                        'password'              => Input::get('password'),
                        'password_confirmation' => Input::get('password_confirmation'),
                        'lang'                  => Input::get('lang')
                    ],
                    [
                        'name'     => 'required',
                        'surname'  => 'required',
                        'email'    => 'required|email',
                        'password' => 'min:8|confirmed',
                        'lang'     => 'required'
                    ]
                );

                // If the user tries to change his e-mail, check if there is already another user with that e-mail adress
                // (this happens in the try-catch block, if the try fails, it means there is no other user with the same
                // e-mail adress, which means that we can safely update the user's e-mail
                if ($selectedUser->email != Input::get('email')) {

                    try {
                        // Attempt to find the user by the new e-mail adress
                        $user2 = Sentry::findUserByCredentials(['email' => Input::get('email')]);

                        // Add an error message in the message collection (MessageBag instance)
                        $validator->getMessageBag()->add(
                            'email',
                            Lang::get('validation.unique', ['attribute ' => 'email '])
                        );

                    } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                        // The e-mail adress wasn't found in the database, so we can safely change the e-mail adress
                        $selectedUser->email = Input::get('email');
                    }
                }

                // If the validation fails, go to previous page with errors
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);

                } else {

                    // Check if the user tried to change his password, if so, update it
                    if (Input::get('password')) {
                        $selectedUser->password = Input::get('password');
                    }

                    // Update $selectedUser fields
                    $selectedUser->first_name = e(Input::get('name'));
                    $selectedUser->last_name  = e(Input::get('surname'));

                    // If the user is editing himself, update current language
                    if($user->id == $selectedUser->id) {

                        $selectedUser->lang = e(Input::get('lang'));

                        Session::forget('lang');
                        Session::put('lang', Input::get('lang'));
                        //Set the language
                        App::setLocale(Session::get('lang'));
                    }

                    // Store updated user in the database
                    $selectedUser->save();

                    return Redirect::route('calendar.index');
                }

                return Redirect::back();
            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
    }

    /**
     * Remove a user from selected group
     * @param $id
     * @param $groupId
     */
    public function removeFromGroup($id, $groupId)
    {
        if (Sentry::check()) {

            try {
                // Find the user using the user id
                $selectedUser = Sentry::findUserById($id);
                $user = Sentry::getUser();
                $group = Sentry::findGroupById($groupId);

            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $error = 'User was not found.';

                // Return to the previous page
                return Redirect::back()->with('error', $error);

            } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
                $error = 'Group was not found.';

                // Return to the previous page
                return Redirect::back()->with('error', $error);
            }

            // Permission checks
            if (($selectedUser->hasAccess('admin') && $selectedUser->school_id == $user->school_id)
                || $user->hasAccess('school')) {

                /**
                 * Check if the selected user is in the admins group,
                 * ->true: check if he is the last person in that group
                 *          -> true: don't allow user to be removed (school needs 1 admin at least)
                 *          -> false: delete user from group
                 * ->false: safe to remove user from group
                 */

                $school = School::find($selectedUser->school_id);
                // Make sure the user can not remove the last user from the school_admin group
                // otherwise no one is left to configure the group (except for the superAdmin)
                if ($group->name == $school->short . '_admin') {
                    $users = Sentry::findAllUsersInGroup($group);

                    // If there is more than 1 user in the admin group, it's safe to delete this one
                    if (count($users) > 1) {
                        // Delete the user
                        $selectedUser->removeGroup($group);

                        // Return to the previous page
                        Redirect::route('group.edit', $group->id);
                    } else {
                        // If there is only 1 or less users in the admin group, do not delete it
                        $error = "You can't remove this user.";

                        // Return to the previous page
                        Redirect::route('group.edit', $group->id)->with('error', $error);
                    }

                } else {
                    // Remove the user from group
                    $selectedUser->removeGroup($group);

                    // Return to the previous page
                    return Redirect::back();
                }
            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
    }

    /**
     * Log out method, logs the user out
     * @return mixed
     */
    public function logout()
    {
        // If user is logged in, then log out the user
        if (Sentry::check()) {
            Sentry::logout();
        }

        // Redirect to landing
        return Redirect::route('landing');
    }

    /**
     * Method for adding users to a group
     *
     * @param $group_id
     * @return mixed
     */
    public function addToGroup($group_id)
    {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            // Find the group using the group id
            $group = Sentry::findGroupById($group_id);

            // Permission checks
            if ($user->hasAccess('school') || ($user->hasAccess('user') && $user->school_id == $group->school_id)) {
                // Find the selected user and try to add him to the correct group
                $user = Sentry::findUserById(Input::get('user'));
                $user->addGroup($group);

                return Redirect::back();

            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
    }
}