<?php

class UserController extends \BaseController {

    protected $layout = 'layout.master';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

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
                return Redirect::to('home');
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
                return Redirect::route('index')
                ->withInput()
                ->with('errorMessage', $errorMessage);
            }
        }

    public function store()
    {
        Sentry::register(array(
            'email'    => 'john.doe@example.com',
            'password' => 'foobar',
        ));
    }

    // Log out function
    public function logout()
    {
        // If user is logged in, then log out the user
        if (Sentry::check()) {
            Sentry::logout();
            // Redirect to root
            return Redirect::to('/');
        }
    }
}