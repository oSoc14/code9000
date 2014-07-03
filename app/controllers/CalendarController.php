<?php
class CalendarController extends \BaseController {

    //MASTER LAYOUT THEMPLATE
    protected $layout = 'layout.master';

	/**
	 * Display a calendar view.
	 *
	 * @return Response
	 */
	public function index()
	{

        if ( ! Sentry::check())
        {
            // User is not logged in, or is not activated
            return Redirect::route('landing');
        }
        else
        {
            return View::make('calendar.index');
        }
    }

    /**
     * Display a listView of the resource.
     *
     * @return Response
     */
    public function listView()
    {
        return View::make('calendar.events');
    }

    /**
     * Return a listing of the resource.
     *
     * @return Response
     */
    public function events()
    {
        if ( ! Sentry::check())
        {
            // User is not logged in, or is not activated
            return Redirect::route('index');
        }
        else
        {
            //GETS THE APPOINTMENTS FROM THE SCHOOL
            $user = Sentry::getUser();
            if ($user->hasAccess('superadmin'))
            {
                $groups = Group::get();
                $groups->load('appointments');;
                //RETURNS JSON RESPONS OFF THE USER
                return Response::json($groups)->setCallback(Input::get('callback'));//return View::make('calendar.events');
            }
            else
            {
                $user->load('school.groups.appointments');
                //RETURNS JSON RESPONS OFF THE USER
                return Response::json($user)->setCallback(Input::get('callback'));//return View::make('calendar.events');
            }

        }
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $user = Sentry::getUser();
        $user->load('school.groups.appointments');
        $groups = $user->school->groups;
        $smartgroup = [];

        foreach($groups as $group){
            $smartgroup[$group->id] = $group->name;
        }

        return View::make('calendar.create')->with('groups',$smartgroup);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        if ( ! Sentry::check())
        {
            // User is not logged in, or is not activated
            return Redirect::route('index');
        }
        else
        {
            $validator = Validator::make(
                array(
                    'group' => Input::get('group'),
                    'description' => Input::get('description'),
                    'end' => Input::get('end'),
                    'start' => Input::get('start'),
                    'title' => Input::get('title')
                ),
                array(
                    'group' => 'required',
                    'description' => 'required',
                    'end' => 'required',
                    'start' => 'required',
                    'title' => 'required'
                )
            );
            if ($validator->fails())
            {
                return Redirect::route('event.create')->withInput()->withErrors($validator);
            }
            else{
                $event = new Appointment();
                $event->title = Input::get('title');
                $event->description = Input::get('description');
                $event->start_date = new DateTime(Input::get('start'));
                $event->end_date = new DateTime(Input::get('end'));
                $event->group_id = Input::get('group');
                $event->save();
                return Redirect::route('calendar.index');
            }
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
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = Sentry::getUser();
        $user->load('school.groups.appointments');
        $groups = $user->school->groups;
        $smartgroup = [];

        foreach($groups as $group){
            $smartgroup[$group->id] = $group->name;
        }

        $event = Appointment::find($id);

        return View::make('calendar.edit')->with('groups',$smartgroup)->with('event',$event);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if ( ! Sentry::check())
        {
            // User is not logged in, or is not activated
            return Redirect::route('index');
        }
        else
        {
            $validator = Validator::make(
                array(
                    'group' => Input::get('group'),
                    'description' => Input::get('description'),
                    'end' => Input::get('end'),
                    'start' => Input::get('start'),
                    'title' => Input::get('title')
                ),
                array(
                    'group' => 'required',
                    'description' => 'required',
                    'end' => 'required',
                    'start' => 'required',
                    'title' => 'required'
                )
            );
            if ($validator->fails())
            {
                return Redirect::route('event.edit')->withInput()->withErrors($validator);
            }
            else{
                $event = Appointment::find($id);
                $event->title = Input::get('title');
                $event->description = Input::get('description');
                $event->start_date = new DateTime(Input::get('start'));
                $event->end_date = new DateTime(Input::get('end'));
                $event->group_id = Input::get('group');
                $event->save();
                return Redirect::route('calendar.index');
            }
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
        if ( ! Sentry::check())
        {
            // User is not logged in, or is not activated
            return Redirect::route('index');
        }else{
            $event = Appointment::find($id);
            $event->delete();
            return Redirect::route('calendar.index');
        }
	}


}
