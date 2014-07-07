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
            if ($user->hasAccess('school'))
            {
                $appointments = Appointment::get()->toArray();
                //RETURNS JSON RESPONS OFF THE USER
                return Response::json($appointments)->setCallback(Input::get('callback'));//return View::make('calendar.events');
            }
            else
            {
                $user->load('school.groups.appointments');
                //RETURNS JSON RESPONS OFF THE USER
                $appointments = [];
                foreach ($user->school->groups as $group) {
                    foreach($group->appointments as $appointment){
                        array_push($appointments,$appointment);
                    }
                }
                return Response::json($appointments)->setCallback(Input::get('callback'));//return View::make('calendar.events');
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
        if(Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            $groups = null;
            $schoolName = null;
            if ($user->hasAnyAccess(array('school','event'))){
                if($user->hasAccess(array('school'))){
                    $groups = Group::where('school_id','<>','')->get();
                }else{
                    $user->load('school.groups.appointments');
                    $groups = $user->school->groups;
                }

                $smartgroup = [];
                foreach($groups as $group){
                    $smartgroup[$group->id] = $group->name;
                }
                return View::make('calendar.create')->with('groups',$smartgroup);
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
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
        if(Sentry::check()) {
            $schools = null;
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','event'))){
                $validator = Validator::make(
                    array(
                        'group' => Input::get('group'),
                        'description' => Input::get('description'),
                        'start' => Input::get('start'),
                        'end' => Input::get('end'),
                        'title' => Input::get('title'),
                        'day' => Input::get('day')
                    ),
                    array(
                        'group' => 'required',
                        'description' => 'required',
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
                    if(Input::get('day')){
                        $event->allday = true;
                    }else{
                        $event->allday = false;
                        if((Input::get('end') == '____/__/__ __:__' || Input::get('end') == Input::get('start')) ){
                            $event->end_date = new DateTime(Input::get('start'));
                            $event->end_date->add(new DateInterval('PT1H'));
                        }elseif(new DateTime(Input::get('start'))>=new DateTime(Input::get('end'))){
                            // Add an error message in the message collection (MessageBag instance)
                            $validator->getMessageBag()->add('end', Lang::get('validation.after', array('attribute ' => 'end ', 'date' => Input::get('start'))));
                            // redirect back with inputs and validator instance
                            return Redirect::back()->withErrors($validator)->withInput();
                        }else{
                            $event->end_date = new DateTime(Input::get('end'));
                        }
                    }
                    $event->group_id = Input::get('group');
                    $event->save();
                    return Redirect::route('calendar.index');
                }
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
            return Redirect::route('landing');
        }
    }


    /**
     * Display and event by its ID
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if(Sentry::check()) {
            $schools = null;
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','event'))){
                $event = Appointment::find($id);
                $event->load('group');
                return View::make('calendar.eventDetail')->with('event',$event);
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
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
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','event'))){
                $schools = null;
                // Find active user
                $user = Sentry::getUser();
                if ($user->hasAnyAccess(array('school','event'))){
                    if($user->hasAccess(array('school'))){
                        $groups = Group::where('school_id','<>','')->get();
                    }else{
                        $user->load('school.groups.appointments');
                        $groups = $user->school->groups;
                    }
                    $smartgroup = [];
                    foreach($groups as $group){
                        $smartgroup[$group->id] = $group->name;
                    }

                    $event = Appointment::find($id);

                    return View::make('calendar.edit')->with('groups',$smartgroup)->with('event',$event);
                }else{
                    return Redirect::route('calendar.index');
                }
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
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
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','event'))){
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
                        'start' => 'required',
                        'title' => 'required'
                    )
                );
                if ($validator->fails())
                {
                    return Redirect::route('event.edit',$id)->withInput()->withErrors($validator);
                }
                else{
                    $event = Appointment::find($id);
                    $event->title = Input::get('title');
                    $event->description = Input::get('description');
                    $event->start_date = new DateTime(Input::get('start'));
                    if(Input::get('day')){
                        $event->allday = true;
                    }else{
                        $event->allday = false;
                        if((Input::get('end') == '____/__/__ __:__' || Input::get('end') == Input::get('start')) ){
                            $event->end_date = new DateTime(Input::get('start'));
                            $event->end_date->add(new DateInterval('PT1H'));
                        }elseif(new DateTime(Input::get('start'))>=new DateTime(Input::get('end'))){
                            // Add an error message in the message collection (MessageBag instance)
                            $validator->getMessageBag()->add('end', Lang::get('validation.after', array('attribute ' => 'end ', 'date' => Input::get('start'))));
                            // redirect back with inputs and validator instance
                            return Redirect::back()->withErrors($validator)->withInput();
                        }else{
                            $event->end_date = new DateTime(Input::get('end'));
                        }
                    }
                    $event->group_id = Input::get('group');
                    $event->save();
                    return Redirect::route('calendar.index');
                }
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
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
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','event'))){
                $event = Appointment::find($id);
                $event->delete();
                return Redirect::route('calendar.index');
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
            return Redirect::route('landing');
        }
    }
}
