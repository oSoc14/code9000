<?php


/**
 * Class UserController
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserApiController extends \BaseController
{
    /**
     * Check if a login
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkLoginState()
    {
        if (!Sentry::check()) {
            return ApiController::createApiAccessError("You are not logged in");
        }

        return ApiController::createApiOk('Logged in');
    }

    /**
     * Get a user by ID
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUser($id)
    {
        try {
            $user = User::find($id);
        } catch (Exception $e) {
            return ApiController::createApiError("User not found");
        }

        return Response::Json($user, 200, [], JSON_NUMERIC_CHECK)->setCallback(Input::get('callback'));
    }

    /**
     * Get a user's calendar id's by the user ID
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserCalendarIds($id)
    {
        try {
            $user = User::find($id);
        } catch (Exception $e) {
            return ApiController::createApiError("User not found");
        }

        return Response::Json($user->calendar_ids, 200, [], JSON_NUMERIC_CHECK)->setCallback(Input::get('callback'));
    }

    /**
     * Creates a new user from the back-office side
     * @return mixed Returns a redirect
     */
    public function createUser()
    {
        // someone has to be logged in
        if (!Sentry::check()) {
            return ApiController::createApiAccessError('You have to login first');
        }

        // Find active user
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == Input::get('school'))) {
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        // Validate inputted data
        $validator = Validator::make(
            [
                'email' => Input::get('email'),
            ],
            [
                'email' => 'required|email|unique:users,email',
            ]
        );

        // If validation fails, return to previous page with errors
        if ($validator->fails()) {
            $validator->getMessageBag()->add('usererror', 'Failed to make a user');

            return ApiController::createApiValidationError($validator->errors());
        }

        // use the school of the admin creating this, or let a superadmin choose a school
        if ($user->hasAccess('superadmin')) {
            $schoolId = Input::get('school_id');
        } else {
            $schoolId = Sentry::getUser()->school->id;
        }

        if ($schoolId == null || $schoolId == 0) {
            $schoolId = Sentry::getUser()->school->id;
        }

        // If the superAdmin tries to make another superAdmin, then schoolId = null, because superadmins
        // don't belong to a school
        if ($user->hasAccess('superadmin') && Input::get('superAdmin')) {
            $schoolId = null;
        }

        // Create a new user
        $created = Sentry::createUser(
            [
                'email' => Input::get('email'),
                'password' => self::generateRandomString(24),
                'activated' => true,
                'school_id' => $schoolId,
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
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

        $created = User::find($created->id);

        // Return to previous page after everything is done
        return Response::Json($created, 200, [], JSON_NUMERIC_CHECK)->setCallback(Input::get('callback'));
    }


    /**
     * Remove a user from a school
     * @param $id = userID
     * @return mixed Returns a redirect
     */
    public function deleteUser($id)
    {
        // If user is logged in, check for permissions
        if (Sentry::check()) {
            // If not logged in, redirect to the login screen
            return ApiController::createApiAccessError('You have to login first');
        }

        $user = Sentry::getUser();

        // Permission check
        if (!$user->hasAnyAccess(['superadmin', 'admin'])) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You do not have the right to perform this action');
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
            if ($user->hasAccess('superadmin')) {
                // Delete the user
                $selectedUser->delete();

                // Return to the previous page
                return ApiController::createApiOk("User deleted");

            } else {
                // Get the school and find its admins
                $school = School::find($selectedUser->school_id);
                $users = SchoolController::getSchoolAdmins($school->id);

                // If there is more than 1 admin in the school
                if (count($users) > 1) {
                    // Delete the user
                    $selectedUser->delete();

                } else {
                    // If there is only 1 user (or less), then we can't delete the user
                    return ApiController::createApiError("You can't remove the last admin of a school");
                }
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ApiController::createApiError("User not found");
        }

        return ApiController::createApiOk("User deleted");

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
            return ApiController::createApiAccessError('You have to login first');
        }

        // Select users
        $user = Sentry::getUser();

        // Try-catch block for trying to find the selected User (to prevent crashing)
        try {
            $selectedUser = Sentry::findUserById($id);
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ApiController::createApiError("User not found!");
        }

        // Check if the user that wants to do the update is either the user himself,
        // or another user with the correct permissions (such as the superAdmin, or an administrator from the school)
        if (!$user->hasAccess('superadmin') && !$user->id == $id && !($user->hasAccess('user')
                && $user->school_id == $selectedUser->school_id)
        ) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError("You don't have the permission to do that!");
        }
        // Validate the inputs
        $validator = Validator::make(
            [
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'email' => Input::get('email'),
                'lang' => Input::get('lang')
            ],
            [
                'first_name' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
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

        // TODO: run specific validation? Required does not apply and passwords don't have to be updated everytime

        // Check if the user tried to change his password, if so, update it
        if (Input::has('password')) {
            $selectedUser->password = Input::get('password');
        }
        if (Input::has('first_name')) {
            // Update $selectedUser fields
            $selectedUser->first_name = e(Input::get('first_name'));
        }
        if (Input::has('last_name')) {
            $selectedUser->last_name = e(Input::get('last_name'));
        }


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

        return Response::Json($selectedUser, 200, [], JSON_NUMERIC_CHECK)->setCallback(Input::get('callback'));

    }


    /**
     * Add a user to the group of people who can edit this calendar.
     * Post parameters: id and calendar_id
     * @return Response
     */
    public function addUserToCalendar()
    {

        if (!Sentry::check()) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You have to login first');
        }

        if (!Input::has('id')) {
            return ApiController::createApiError("Missing user id");
        }
        if (!Input::has('calendar_id')) {
            return ApiController::createApiError("Missing calendar id");
        }

        // Find active user and calendar information
        $user = Sentry::getUser();
        $calendar = Calendar::find(Input::get('calendar_id'));
        $selectedUser = Sentry::findUserById(Input::get('id'));

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == $calendar->school_id)) {
            // If no permissions, redirect the user to the calendar index page
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        // The minimal permission level is editor,
        // so we don't have to check if the selected user has access to editing features

        $school = $calendar->school;
        if ($selectedUser->school_id != $school->id) {
            // If no permissions, redirect the user to the calendar index page
            ApiController::createApiError("The user does not belong to this school!");
        }

        $selectedUser->calendars()->attach($calendar);

        return ApiController::createApiOk("Attached user to calendar!");
    }

    /**
     * Remove a user from the group of people who can edit this calendar.
     * Post parameters: id and calendar_id
     * @return Response
     */
    public function removeUserFromCalendar()
    {

        if (!Sentry::check()) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You have to login first');
        }

        if (!Input::has('id')) {
            return ApiController::createApiError("Missing user id");
        }
        if (!Input::has('calendar_id')) {
            return ApiController::createApiError("Missing calendar id");
        }

        // Find active user and calendar information
        $user = Sentry::getUser();
        $calendar = Calendar::find(Input::get('calendar_id'));
        $selectedUser = Sentry::findUserById(Input::get('id'));

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == $calendar->school_id)) {
            // If no permissions, redirect the user to the calendar index page
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        // The minimal permission level is editor,
        // so we don't have to check if the selected user has access to editing features

        $selectedUser->calendars()->detach($calendar);

        return ApiController::createApiOk("Detached user from calendar");
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
            return ApiController::createApiAccessError('You have to login first');
        }
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAccess('superadmin')) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        // Find the role using by name
        $role = Sentry::findGroupByName('admin');

        // Find the selected user and try to add him to the correct role
        $user = Sentry::findUserById($userId);
        $user->addGroup($role);

        return ApiController::createApiOk("Promoted user");
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
            return ApiController::createApiAccessError('You have to login first');
        }

        try {
            // Find the user using the user id
            $selectedUser = Sentry::findUserById($id);
            $user = Sentry::getUser();
            $role = Sentry::findGroupByName('admin');
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return ApiController::createApiError("User not found");
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            return ApiController::createApiError("Admin group not found");
        }

        // Permission checks
        if (!($selectedUser->hasAccess('admin') && $selectedUser->school_id == $user->school_id)
            && !$user->hasAccess('superadmin')
        ) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        $school = School::find($selectedUser->school_id);
        // Make sure the user can not remove the last admin from the school's admins
        // otherwise no one is left to configure the school (except for the superAdmin)

        $users = SchoolController::getSchoolAdmins($school->id);

        // If there is more than 1 user with admin rights, it's safe to delete this one
        if (count($users) <= 1) {
            return ApiController::createApiError("The last admin user cannot be removed");
        }
        // Delete the user
        $selectedUser->removeGroup($role);

        // Return to the previous page
        return ApiController::createApiOk("Demoted user");

    }

    /**
     * Request a mail to be sent with a reset link
     *
     * @return boolean
     */
    public function sendResetLink($id)
    {
        try {

            $user = User::find($id);
            if (Sentry::getUser()->school->id != $user->School->id || !Sentry::getUser()->hasAccess('admin')) {
                return ApiController::createApiAccessError("You are not allowed to perform this action");
            }

            $resetCode = $user->getResetPasswordCode();
            $url = URL::route('user.resetPassword', [$resetCode]);

            Mail::send('emails.adminpasswordreset', array('url' => $url), function ($message) use ($user) {
                $message->to($user->email,
                    $user->firstname . ' ' . $user->lastname)->subject('Educal: reset wachtwoord');
            });

            \Log::info("Sent an email to $email, with the reset link: " . $url);

        } catch (ModelNotFoundException $ex) {

            return ApiController::createApiError("User not found");
        }

        return ApiController::createApiOk("Mail sent");

    }

    private static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
