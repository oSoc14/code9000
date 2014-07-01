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
    // Do something ;)
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
    }*/
  }
}