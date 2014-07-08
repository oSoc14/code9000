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

}
