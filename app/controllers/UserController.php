<?php


/**
 * Class UserController
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends \BaseController
{

    protected $layout = 'layout.master';

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
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'email' => Input::get('email'),
                'school' => Input::get('school'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'tos' => Input::get('tos'),
                'honey' => 'honeypot',
                'honey_time' => 'required|honeytime:5'
            ],
            [
                'first_name' => 'required',
                'last_name' => 'required',
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
                    'first_name' => e(Input::get('first_name')),
                    'last_name' => e(Input::get('last_name')),
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
