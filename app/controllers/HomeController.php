<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
        if(Sentry::check()) {
            return Redirect::route('calendar.index');
        }else{
            $schools = School::get();
            $schoolsArray = [];
            foreach ($schools as $school){
                $schoolsArray[$school->id] = $school->name;
            }
            return View::make('landing')
                ->with("schools",$schoolsArray);
        }
	}

    public function settings()
    {
        if(Sentry::check()) {
            return View::make('settings');
        }else{
            $schools = School::get();
            $schoolsArray = [];
            foreach ($schools as $school){
                $schoolsArray[$school->id] = $school->name;
            }
            return View::make('landing')
                ->with("schools",$schoolsArray);
        }
    }

    public function settingsUpdate()
    {
        if(Sentry::check()) {
            $validator = Validator::make(
                array(
                    'lang' => Input::get('lang')
                ),
                array(
                    'lang' => 'required'
                )
            );
            if ($validator->fails())
            {
                return View::make('settings')
                    ->withInput()
                    ->withErrors($validator);

            }else{
                $user = Sentry::getUser();
                $user->lang = e(Input::get('lang'));
                $user->save();
                Session::forget('lang');
                Session::put('lang', Input::get('lang'));
                //Set the language
                App::setLocale(Session::get('lang'));

                return View::make('settings');
            }
        }else{
            $schools = School::get();
            $schoolsArray = [];
            foreach ($schools as $school){
                $schoolsArray[$school->id] = $school->name;
            }
            return View::make('landing')
                ->with("schools",$schoolsArray);
        }
    }

}
