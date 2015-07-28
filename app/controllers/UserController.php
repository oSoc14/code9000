<?php


/**
 * Class UserController
 * This controller handles the CRUD and authentications of users.
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends \BaseController
{

    protected $layout = 'layout.master';

    /**
     * Get all users for a school, where they can be activated as well by the administration
     * Show these on view
     *
     * @return mixed
     */

    // TODO: Make permissions a lot better
    public function index()
    {
        // If user is logged in, get school_id, find respective users
        if (!Sentry::check()) {

            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }

        // Get info from the logged in user
        $user = Sentry::getUser();

        // Make an empty users object which will hold all the users from a certain school
        $users = null;

        $schools = School::get();
        $schoolsArray = [];

        foreach ($schools as $school) {
            $schoolsArray[$school->id] = $school->name;
        }

        if ($user->hasAccess('superadmin')) {
            $users = User::where('id', '<>', $user->id)->get();
            $school = null;

        } elseif ($user->hasAccess('admin')) {

            // If user is no superAdmin, display users based on the logged in user's school
            $schoolId = $user->school_id;

            // Get all users with this school_id, except for the logged in user
            $users = User::where('school_id', $schoolId)
                ->where('id', '<>', $user->id)
                ->get();

            $school = School::where('id', $schoolId)->first();

        } else {
            return Redirect::route('calendar.redirect');
        }

        return View::make('user.index')
            ->with('users', $users)
            ->with('school', $school)
            ->with('schools', $schoolsArray);
    }


    /**
     * Authenticate users
     * Reset a user's password, if the user + hash doesn't match, return to the landing page
     *
     * @param string $hash The hash of the reset password procedure
     *
     * @return Response
     */
    public function resetPassword($hash)
    {
        $method = \Request::method();

        if ($method == 'GET') {
            try {
                // Find the user using the user id
                $user = User::where('reset_password_code', $hash)->firstOrFail();

                // Check if the reset password code is valid
                if ($user->checkResetPasswordCode($hash)) {

                    return View::make('user.password')->with(['user' => $user, 'hash' => $hash]);
                } else {
                    // The provided password reset code is Invalid
                    return Redirect::back()->withInput();
                }
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                return Redirect::route('landing');
            } catch (ModelNotFoundException $ex) {
                return Redirect::route('landing');
            }
        } else {
            if ($method == 'POST') {

                $user = User::where('reset_password_code', $hash)->first();

                if (empty($user)) {
                    return Redirect::route('landing');
                }

                // Set a hasher, this is lost because we use our own user model
                $user->setHasher(new Cartalyst\Sentry\Hashing\NativeHasher);

                // Check if the reset password code is valid (redundant)
                if ($user->checkResetPasswordCode($hash)) {

                    $post = Input::all();
                    $password = $post['password'];
                    $confirmation = $post['password_confirmation'];

                    $validator = Validator::make(
                        [
                            'password' => $password,
                            'password_confirmation' => $confirmation
                        ],
                        [
                            'password' => 'required|min:8|confirmed',
                        ]
                    );

                    if ($validator->fails()) {

                        $validator->getMessageBag()->add('usererror', 'Failed to reset your password.');

                        return Redirect::route('user.resetPassword', [$hash])
                            ->withInput()
                            ->withErrors($validator);

                    } else {

                        $user->password = $password;

                        $user->save();

                        \Log::info("Successfully reset the password for user with id $user->id.");

                        return Redirect::back()->withInput(['email' => $user->email]);
                    }
                } else {
                    // The provided password reset code is invalid
                    return Redirect::route('landing');
                }
            }
        }
    }

    /**
     * Request a mail to be sent with a reset link
     *
     * @return boolean
     */
    public function sendResetLink($email)
    {
        try {

            $user = User::where('email', $email)->firstOrFail();

            $resetCode = $user->getResetPasswordCode();

            $url = URL::route('user.resetPassword', [$resetCode]);

            $message = '<html><body><p>' . ucfirst(trans('reminders.resetmail')) . ': <a href="' . $url . '">' . $url . '</a></p></body></html>';

            $headers = "MIME-Version: 1.0\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\n";

            $result = mail($email, 'Educal: Reset wachtwoord', $message, $headers);

            \Log::info("Sent an email to $email, with the reset link: " . $url);

            return Redirect::route('landing')
                ->withInput(['email-success' => 'Er werd een mail gestuurd met verdere instructies.']);

        } catch (ModelNotFoundException $ex) {

            return Redirect::route('landing')
                ->withInput(['email-reset' => $email])
                ->withErrors(["message" => "Het email adres werd niet gevonden."]);
        }

    }

    /**
     * TODO: Custom variable error messages (multiple language support)
     * @return mixed
     */
    public function auth()
    {
        try {
            // Login credentials
            $credentials = [
                'email' => Input::get('lemail'),
                'password' => Input::get('password'),
            ];

            // Authenticate the user
            $user = Sentry::authenticate($credentials, false);

            // If "remember me" is checked, make cookie, if not, don't make a cookie
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
            return Redirect::route('calendar.redirect');

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

        // If there is an errormessage, return to login page
        // With errorMessage
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
                'name' => Input::get('name'),
                'surname' => Input::get('surname'),
                'email' => Input::get('email'),
                'school' => Input::get('school'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'tos' => Input::get('tos'),
                'honey' => 'honeypot',
                'honey_time' => 'required|honeytime:5'
            ],
            [
                'name' => 'required',
                'surname' => 'required',
                'school' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'tos' => 'required'
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
            $user = Sentry::createUser(
                [
                    'email' => Input::get('email'),
                    'password' => Input::get('password'),
                    'activated' => false,
                    'school_id' => Input::get('school'),
                    'first_name' => e(Input::get('name')),
                    'last_name' => e(Input::get('surname')),
                ]
            );

            UserController::checkCreateRoles(); // make sure the roles are created already

            // Find the role using the role name
            $editorRole = Sentry::findGroupByName('editor');

            // Assign the role to the user
            $user->addGroup($editorRole);

            return Redirect::route('landing');
        }
    }

    /**
     * Creates a new user from the back-office side
     * @return mixed Returns a redirect
     */
    public function create()
    {
        // someone has to be logged in
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        // Find active user
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == Input::get('school'))) {
            return Redirect::back()
                ->withInput()
                ->withErrors("Je hebt niet de benodigde rechten om dit te doen!");
        }

        // Validate inputted data
        $validator = Validator::make(
            [
                'name' => Input::get('name'),
                'surname' => Input::get('surname'),
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'school' => Input::get('school'),
            ],
            [
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'school' => 'required|integer',
            ]
        );

        // If validation fails, return to previous page with errors
        if ($validator->fails()) {
            $validator->getMessageBag()->add('usererror', 'Failed to make a user');

            return Redirect::back()
                ->withInput()
                ->withErrors($validator);

        }
        // If there are no validation errors, handle data in the correct way
        // Get schoolId from the input field
        $schoolId = Input::get('school');

        // If the superAdmin tries to make another superAdmin, then schoolId = null, because superadmins
        // don't belong to a school
        if ($user->hasAccess('superadmin') && Input::get('superAdmin')) {
            $schoolId = null;
        }

        // Create a new user
        $created = Sentry::createUser(
            [
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'activated' => true,
                'school_id' => $schoolId,
                'first_name' => Input::get('name'),
                'last_name' => Input::get('surname'),
            ]
        );

        // If a superAdmin was created, then we add him to the superadmin role in the database, which is the
        // superadmin role
        if ($user->hasAccess('superadmin') && Input::get('superAdmin')) {
            $role = Sentry::findGroupByName('superadmin');

        } else {
            $role = Sentry::findGroupByName('editor');
        }
        $created->addGroup($role); // give role to user

        // Return to previous page after everything is done
        return Redirect::route('user.index');
    }


    /**
     * Remove a user from a school
     * @param $id = userID
     * @return mixed Returns a redirect
     */
    public function removeUserFromSchool($id)
    {
        // If user is logged in, check for permissions
        if (Sentry::check()) {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Permission check
        if (!$user->hasAnyAccess(['school', 'admin'])) {
            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }

        try {
            // Find the user using the user id
            $selectedUser = Sentry::findUserById($id);

            /**
             * Check if the selected user has the admin role,
             * ->true: check if he is the last person in the school with this role
             *          -> true: don't allow user to be removed (school needs 1 admin at least)
             *          -> false: delete user from school
             * ->false: safe to remove user from school
             */
            if ($selectedUser->hasAccess('admin')
                && ($selectedUser->school_id == $user->school_id || $user->hasAccess('superadmin'))
            ) {
                // Get the school and find its admins
                $school = School::find($selectedUser->school_id);
                $users = SchoolController::getSchoolAdmins($school->id);

                // If there is more than 1 admin in the school
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
    }

    /**
     * Activate a user so that he gets access to the school (as a teacher for example)
     * @param $id = userID
     * @return mixed
     */
    public function activateUser($id)
    {
        // If user is logged in, get school_id, find respective users
        if (!Sentry::check()) {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // Permission check
        if (!$user->hasAnyAccess(['superadmin', 'admin'])) {
            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }

        try {
            // Find the user using the user id
            $selectedUser = Sentry::findUserById($id);

            // Check if all permission are in order, if ok, just progress through the script, else we handle errors
            // A user can't deactivate/activate himself, he needs to have "user" permissions, be in the same
            // school.
            // Alternatively, a superAdmin can do all
            if (!($user->school_id == $selectedUser->school_id || $user->hasAccess('superadmin')) || $selectedUser->id == $user->id
            ) {
                // Permissions not ok, return to calendar index
                return Redirect::route('calendar.redirect');
            }

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

        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Redirect::route('calendar.redirect');
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
        if (!Sentry::check()) {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }

        $user = Sentry::getUser();

        // If id is given, find user by that id
        if ($id != null) {

            try {
                $selectedUser = Sentry::findUserById($id);
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                return Redirect::route('calendar.redirect');
            }

        } else {
            // If no id is given, make the id equal to the id of the logged in user (User is trying to edit himself)
            $selectedUser = Sentry::getUser();
            $id = $selectedUser->id;
        }

        // Check permissions for the user (user has to be either a superAdmin, edit himself, or have user permissions
        // for the same school as the user he is trying to edit
        if ($user->hasAccess('superadmin') || $user->id == $id
            || ($user->hasAccess('user') && $user->school_id == $selectedUser->school_id)
        ) {

            return View::make('user.edit')->with('user', $selectedUser);

        } else {
            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }

    }

    /**
     * Update userSettings
     * @param $id = userID
     * @return mixed
     */
    public function updateUser($id)
    {
        if (!Sentry::check()) {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }

        // Select users
        $user = Sentry::getUser();

        // Try-catch block for trying to find the selected User (to prevent crashing)
        try {
            $selectedUser = Sentry::findUserById($id);

        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Redirect::route('calendar.redirect');
        }

        // Check if the user that wants to do the update is either the user himself,
        // or another user with the correct permissions (such as the superAdmin, or an administrator from the school)
        if (!$user->hasAccess('superadmin') && !$user->id == $id && !($user->hasAccess('user')
                && $user->school_id == $selectedUser->school_id)
        ) {
            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }
        // Validate the inputs
        $validator = Validator::make(
            [
                'name' => Input::get('name'),
                'surname' => Input::get('surname'),
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'lang' => Input::get('lang')
            ],
            [
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required|email',
                'password' => 'min:8|confirmed',
                'lang' => 'required'
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
            $selectedUser->last_name = e(Input::get('surname'));

            // If the user is editing himself, update current language
            if ($user->id == $selectedUser->id) {

                $selectedUser->lang = e(Input::get('lang'));

                Session::forget('lang');
                Session::put('lang', Input::get('lang'));
                //Set the language
                App::setLocale(Session::get('lang'));
            }

            // Store updated user in the database
            $selectedUser->save();

            return Redirect::route('calendar.redirect');
        }

        return Redirect::back();

    }

    /**
     * Remove a user from selected calendar
     * @param $id int the ID of the user to demote
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeAdminRole($id)
    {
        if (!Sentry::check()) {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }

        try {
            // Find the user using the user id
            $selectedUser = Sentry::findUserById($id);
            $user = Sentry::getUser();
            $role = Sentry::findGroupByName('admin');
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
        if (!($selectedUser->hasAccess('admin') && $selectedUser->school_id == $user->school_id)
            && !$user->hasAccess('superadmin')
        ) {
            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }

        $school = School::find($selectedUser->school_id);
        // Make sure the user can not remove the last admin from the school's admins
        // otherwise no one is left to configure the school (except for the superAdmin)

        $users = SchoolController::getSchoolAdmins($school->id);

        // If there is more than 1 user with admin rights, it's safe to delete this one
        if (count($users) > 1) {
            // Delete the user
            $selectedUser->removeGroup($role);

            // Return to the previous page
            Redirect::route('calendarManagement.index');
        } else {
            // If there is only 1 or less users with the admin role, do not delete it
            $error = "You can't remove this user.";

            // Return to the previous page
            Redirect::route('calendarManagement.index')->with('error', $error);
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
     * Method for making users admin.
     *
     * @param $userId the ID of the user to rank up
     * @return mixed
     */
    public function addAdminRole($userId)
    {
        if (!Sentry::check()) {
            // If not logged in, redirect to the login screen
            return Redirect::route('landing');
        }
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAccess('superadmin')) {
            // If no permissions, redirect to calendar index
            return Redirect::route('calendar.redirect');
        }

        // Find the role using by name
        $role = Sentry::findGroupByName('admin');

        // Find the selected user and try to add him to the correct role
        $user = Sentry::findUserById($userId);
        $user->addGroup($role);

        return Redirect::back();
    }

    /**
     * Check if the sentry roles already exist, if not, create them
     */
    static function checkCreateRoles()
    {

        if (Role::all()->count() > 2) {
            return;
        }

        Sentry::createGroup(array(
            'name' => 'superadmin',
            'permissions' => array(
                'superadmin' => 1,
                'admin' => 1,
                'editor' => 1,
                'user' => 1,
            ),
        ));

        Sentry::createGroup(array(
            'name' => 'admin',
            'permissions' => array(
                'admin' => 1,
                'editor' => 1,
                'user' => 1,
            ),
        ));

        Sentry::createGroup(array(
            'name' => 'editor',
            'permissions' => array(
                'editor' => 1,
                'user' => 1,
            ),
        ));

    }

}
