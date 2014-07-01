<?php

class UserController extends \BaseController {

  protected $layout = 'layout.master';

    /**
     * Display a Loginform
     *
     * @return Response
     */
    public function index()
    {
        $this->layout->content = View::make('index');
    }

    public function auth()
    {
        //print_r(Input::get('remember'));
          try
          {
              // Login credentials
              $credentials = array(
                  'email'    => Input::get('email'),
                  'password' => Input::get('password'),
              );

              // Authenticate the user
              $user = Sentry::authenticate($credentials, false);
              Sentry::loginAndRemember($user);

              return Redirect::to('home');
          }
          catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
          {
              echo 'Login field is required.';
          }
          catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
          {
              echo 'Password field is required.';
          }
          catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
          {
              echo 'Wrong password, try again.';
          }
          catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
          {
              echo 'User was not found.';
          }
          catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
          {
              echo 'User is not activated.';
          }

            // The following is only required if the throttling is enabled
          catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
          {
              echo 'User is suspended.';
          }
          catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
          {
              echo 'User is banned.';
          }
    }

    public function store(){
        Sentry::register(array(
            'email'    => 'john.doe@example.com',
            'password' => 'foobar',
        ));
    }



    /*$validator = Validator::make(Input::all(), $rules);

    if ($validator->passes()) {
      $credentials = [
        'email'      => Input::get('email_login'),
        'password'   => Input::get('password_login'),
        'deleted_at' => null, // Extra voorwaarde.
      ];
      $remember = Input::get('switch-auth') == 'remember'; // Onthoud authenticatie.

      if (Auth::attempt($credentials, $remember)) {

        return Redirect::route('map');
      } else {

        return Redirect::route('landing')
          ->withInput()             // Vul het formulier opnieuw in met de Input.
//                    ->with('auth-error-message', 'U heeft een onjuiste gebruikersnaam of een onjuist wachtwoord ingevoerd.')
          ;
      }
    } else {

      return Redirect::route('landing') // Zie: $ php artisan routes
        ->withInput()             // Vul het formulier opnieuw in met de Input.
        ->withErrors($validator); // Maakt $errors in View.
    }
  }*/
}