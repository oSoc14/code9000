<?php

class SchoolController extends \BaseController {

    protected $layout = 'layout.master';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $user = Sentry::getUser();
        // If user is logged in, redirect to calendar index
        if(Sentry::check()) {
            if ($user->hasAccess('school'))
            {
                $schools = School::get();
                $this->layout->content = View::make('school.index')->with('schools',$schools);
            }
            else
            {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $validator = Validator::make(
            array(
                'name' => Input::get('sname'),
                'email' => Input::get('semail'),
                'city' => Input::get('city'),
                'password' => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
                'tos' => Input::get('tos')
            ),
            array(
                'name' => 'required|unique:schools,name',
                'city' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'tos' => 'required'
            )
        );
        if ($validator->fails())
        {
            $validator->getMessageBag()->add('schoolerror', 'Failed to make a school');
            return Redirect::route('landing')->withInput()
                ->withErrors($validator);

        }
        else{
            $school = new School();
            $school->name = e(Input::get("sname"));
            $short = e(strtolower(Input::get("sname")));
            $short = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $short));
            $school->short = $short;
            $school->city = e(Input::get("city"));
            $school->save();

            Sentry::createGroup(array(
                'name'        => $short.'_global',
                'permissions' => array(
                    'school'    => 0,
                    'admin'     => 0,
                    'user'      => 0,
                    'group'    => 0,
                    'event'    => 0,
                ),
                'school_id'     => $school->id,
            ));

            $group = Sentry::createGroup(array(
                'name'        => $short.'_schooladmin',
                'permissions' => array(
                    'school'    => 0,
                    'admin'     => 1,
                    'user'      => 1,
                    'group'    => 1,
                    'event'    => 1,
                ),
                'school_id'     => $school->id,
            ));

            $user = Sentry::createUser(array(
                'email'    => Input::get("semail"),
                'password' => Input::get("password"),
                'activated' => true,
                'school_id' => $school->id,
            ));

            $user->addGroup($group);
            Sentry::login($user, false);

            return Redirect::route('calendar.index');
        }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        // If user is logged in, redirect to calendar index
        if(Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->hasAccess('school'))
            {
                $school = School::find($id);
                $school->load("groups");
                $this->layout->content = View::make('school.detail')->with('school',$school);
            }
            else
            {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        if(Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->hasAccess(array('school')))
            {
                $school = School::find($id);
                $this->layout->content = View::make('school.edit')->with('school',$school);
            }
            else
            {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if(Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->hasAccess('school'))
            {
                $school = School::find($id);
                if(Input::get('name') != $school->name){
                    $validator = Validator::make(
                        array(
                            'name' => Input::get('name'),
                            'city' => Input::get('city'),
                        ),
                        array(
                            'name' => 'required|unique:schools,name',
                            'city' => 'required',
                        )
                    );
                    if ($validator->fails())
                    {
                        return Redirect::route('school.edit',$id)
                            ->withInput()
                            ->withErrors($validator);
                    }else{
                        $short = e(strtolower(Input::get("name")));
                        $short = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $short));
                        foreach($school->groups as $group){
                            $group->name = str_replace($school->short, $short, $group->name);
                            $group->save();
                        }
                        $school->short = $short;
                        $school->name = e(Input::get("name"));
                        $school->city = e(Input::get("city"));
                        $school->save();
                        return Redirect::route('school.index');
                    }
                }else{
                    $school->name = e(Input::get("name"));
                    $school->city = e(Input::get("city"));
                    $school->save();
                    return Redirect::route('school.index');
                }
            }
            else
            {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->hasAccess(array('school')))
            {
                $school = School::find($id);
                $school->delete();
                return Redirect::route('school.index');
            }
            else
            {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }
	}


}
