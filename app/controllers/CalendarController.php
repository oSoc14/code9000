<?php

/**
 * Class CalendarController
 * This controller is the main controller of the application. It handles the CRUD of all events.
 */
class CalendarController extends \BaseController
{

    /**
     * Display a calendar view to logged in user
     * @return Response
     */
    public function index()
    {
        if (!Sentry::check()) {
            // User is not logged in, or is not activated
            return Response::view('landing', [], 401);
        } else {
            return View::make('calendar.index');
        }
    }

    /**
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
     */
    public function events()
    {
        if (!Sentry::check()) {
            // User is not logged in, or is not activated
            return Response::view('landing', [], 401);
        } else {
            // Gets all appointments from the school
            $user = Sentry::getUser();

            // Check if user is superAdmin
            if ($user->hasAccess('school')) {
                $appointments = Appointment::get()->load('group.school')->toArray();
                // Returns JSON response of the user
                return Response::json($appointments)->setCallback(
                    Input::get('callback')
                ); //return View::make('calendar.events');

            } else {
                // If user is not superAdmin, show groups based on the school of the logged in user
                $user->load('school.groups.appointments.group.school');
                $appointments = [];

                // Loop through groups to get all appointments
                foreach ($user->school->groups as $group) {
                    foreach ($group->appointments as $appointment) {
                        array_push($appointments, $appointment);
                    }
                }
                // Returns JSON response of the user
                return Response::json($appointments)->setCallback(Input::get('callback'));
            }
        }
    }


    /**
     * Show the form for creating a new appointment.
     *
     * @return Response
     */
    public function create()
    {
        if (Sentry::check()) {
            // Find active user and set default variables to null
            $user       = Sentry::getUser();
            $groups     = null;
            $schoolName = null;

            // Permission checks
            if ($user->hasAnyAccess(['school', 'event'])) {

                // If user is a superAdmin, show all possible groups to add an event to
                if ($user->hasAccess(['school'])) {
                    $groups = Group::where('school_id', '<>', '')->get();
                    $opening = '';
                } else {
                    // If the user isn't a superAdmin, only show the groups to which the user has permissions
                    $user->load('school.groups.appointments');
                    $groups = $user->school->groups;
                    $opening = $user->school->opening;
                }

                // Transform recieved objectList (from database) into array to send with view
                $smartgroup = [];
                foreach ($groups as $group) {
                    $smartgroup[$group->id] = $group->name;
                }

                // Show the form where users can add appointments
                return View::make('calendar.create')->with('groups', $smartgroup)->with('opening', $opening);

            } else {
                // If no permissions, redirect the user to the calendar index page
                return Response::view('calendar.index', [], 401);
            }
        } else {
            return Response::view('landing', [], 401);
        }
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @return Response
     */

    // TODO: Add functionality for parent_events for repeating events, also handle these in the Update and Edit methods
    public function store()
    {
        if (Sentry::check()) {
            // Find active user and set default variables to null
            $schools = null;
            $user    = Sentry::getUser();

            // Permission checks
            if ($user->hasAnyAccess(['school', 'event'])) {

                $endDate = new DateTime();
                // Check if endDate isn't blank
                if (Input::get('end-date') == '') {
                    $endDate = null;
                }

                $validator = Validator::make(
                    [
                        'group'       => Input::get('group'),
                        'description' => Input::get('description'),
                        'start-date'  => Input::get('start-date'),
                        'end-date'    => $endDate,
                        'start-time'  => Input::get('start-time'),
                        'end-time'    => Input::get('end-time'),
                        'title'       => Input::get('title'),
                        'day'         => Input::get('day')
                    ],
                    [
                        'group'       => 'required',
                        'description' => 'required',
                        'start-date'  => 'date',
                        'end-date'    => 'date',
                        'start-time'  => 'required|date_format:H:i',
                        'end-time'    => 'required|date_format:H:i',
                        'title'       => 'required'
                    ]
                );

                // If validation fails, return to the create form with errors.
                if ($validator->fails()) {
                    return Redirect::route('event.create')->withInput()->withErrors($validator);
                } else {
                    $title       = e(Input::get('title'));
                    $description = e(Input::get('description'));
                    $location    = e(Input::get('location'));
                    $group_id    = Input::get('group');
                    $start_date  = e(Input::get('start-date'));
                    $end_date    = e(Input::get('end-date'));
                    $start_time  = e(Input::get('start-time'));
                    $end_time    = e(Input::get('end-time'));

                    // TODO: Handle All day events, or decide to remove it alltogether
                    // If the event isn't the whole day, determine the end date/time
                    //$event->allday = false;

                    // Recurring events handling
                    if (Input::get('repeat')) {

                        $dateArray = explode(',', e(Input::get('repeat-dates')));
                        // Check if there are any dates selected, return error if not
                        if(count($dateArray) == 0) {

                            $validator->getMessageBag()->add(
                                'end',
                                Lang::get('validation.countmin', ['attribute ' => 'Jaarkalender ', 'min' => '1'])
                            );

                            // Redirect back with inputs and validator instance
                            return Redirect::back()->withErrors($validator)->withInput();

                        } else {
                            // Loop through dates to validate them
                            foreach($dateArray as $da) {
                                // If date is invalid, return error
                                if (!self::validateDate($da)) {

                                    $validator->getMessageBag()->add(
                                        'end',
                                        Lang::get('validation.date_format', ['attribute ' => 'Jaarkalender '])
                                    );
                                    // Redirect back with inputs and validator instance
                                    return Redirect::back()->withErrors($validator)->withInput();
                                }
                            }
                            // If all dates are validated and correct, create parent appointment and children
                            $parent              = new AppParent();
                            $parent->title       = $title;
                            $parent->description = $description;
                            $parent->location    = $location;
                            $parent->group_id    = $group_id;
                            $parent->save();

                            foreach($dateArray as $da) {
                                $event              = new Appointment();
                                $event->title       = $title;
                                $event->description = $description;
                                $event->location    = $location;
                                $event->group_id    = $group_id;
                                $event->start_date  = new DateTime($da . ' ' . $start_time);
                                $event->end_date    = new DateTime($da . ' ' . $end_time);
                                $event->parent_id   = $parent->id;
                                $event->save();
                            }
                        }
                        return Redirect::route('calendar.index');
                    } else {

                        if(!$start_date) {
                            $validator->getMessageBag()->add(
                                'end',
                                Lang::get('validation.required', ['attribute ' => 'start-date '])
                            );

                            return Redirect::back()->withErrors($validator)->withInput();
                        } else {
                            $sd = new DateTime($start_date . ' ' . $start_time);

                            if($end_date == '') {
                                $end_date = $start_date;
                            }
                            $ed = new DateTime($end_date . ' ' . $end_time);

                            // Check if end date is before start date, if so, return with error
                            if($sd >= $ed) {

                                $validator->getMessageBag()->add(
                                    'end',
                                    Lang::get('validation.after', ['attribute ' => 'end-date ', 'date' => Input::get('start-date')])
                                );

                                // Redirect back with inputs and validator instance
                                return Redirect::back()->withErrors($validator)->withInput();

                            } else {
                                $event              = new Appointment();
                                $event->title       = $title;
                                $event->description = $description;
                                $event->location    = $location;
                                $event->group_id    = $group_id;
                                $event->start_date  = $sd;
                                $event->end_date    = $ed;

                                $event->save();
                                return Redirect::route('calendar.index');
                            }
                        }
                    }
                }
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Response::view('calendar.index', [], 401);
            }
        } else {
            return Response::view('landing', [], 401);
        }
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();

            // Check permissions
            if ($user->hasAnyAccess(['school', 'event'])) {
                $event = Appointment::find($id);

                // Check if user is superAdmin
                if ($user->hasAccess(['school'])) {
                    $groups = Group::where('school_id', '<>', '')->get();

                } elseif ($user->school_id == $event->group->school_id) {
                    // Check if User belongs to group/school which the appointment is from
                    $user->load('school.groups.appointments');
                    $groups = $user->school->groups;

                } else {
                    return Redirect::route('calendar.index');
                }

                // Make a list of all the groups in a school to show with the view
                $smartgroup = [];
                foreach ($groups as $group) {
                    $smartgroup[$group->id] = $group->name;
                }

                $event = Appointment::find($id);

                return View::make('calendar.edit')->with('groups', $smartgroup)->with('event', $event);
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Response::view('calendar.index', [], 401);
            }
        } else {
            return Response::view('landing', [], 401);
        }
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (Sentry::check()) {
            // Find active user
            $user  = Sentry::getUser();
            $event = Appointment::find($id);

            // Check if User belongs to group/school which the appointment is from
            if ($user->hasAccess('school') || ($user->hasAccess(
                        'event'
                    ) && $user->school_id == $event->group->school_id)
            ) {
                $endDate = new DateTime();
                // Check if endDate isn't blank
                if (Input::get('end-date') == '') {
                    $endDate = null;
                }

                $validator = Validator::make(
                    [
                        'group'       => Input::get('group'),
                        'description' => Input::get('description'),
                        'start-date'  => Input::get('start-date'),
                        'end-date'    => $endDate,
                        'start-time'  => Input::get('start-time'),
                        'end-time'    => Input::get('end-time'),
                        'title'       => Input::get('title'),
                        'day'         => Input::get('day')
                    ],
                    [
                        'group'       => 'required',
                        'description' => 'required',
                        'start-date'  => 'date',
                        'end-date'    => 'date',
                        'start-time'  => 'required|date_format:H:i',
                        'end-time'    => 'required|date_format:H:i',
                        'title'       => 'required'
                    ]
                );

                if ($validator->fails()) {
                    return Redirect::route('event.edit', $id)->withInput()->withErrors($validator);
                } else {
                    $title       = e(Input::get('title'));
                    $description = e(Input::get('description'));
                    $location    = e(Input::get('location'));
                    $group_id    = Input::get('group');
                    $start_date  = e(Input::get('start-date'));
                    $end_date    = e(Input::get('end-date'));
                    $start_time  = e(Input::get('start-time'));
                    $end_time    = e(Input::get('end-time'));
                    $parents     = Input::get('par');

                    // TODO: Handle All day events, or decide to remove it alltogether
                    // TODO: Update date/time if needed
                    // If the event isn't the whole day, determine the end date/time
                    //$event->allday = false;

                    // Recurring events handling
                    if ($event->parent_id) {
                        if($parents) {
                            $parent = AppParent::find($event->parent_id);
                            // Update parent event
                            $parent->title       = $title;
                            $parent->description = $description;
                            $parent->location    = $location;
                            $parent->group_id    = $group_id;
                            $parent->save();

                            Appointment::where('parent_id', $parent->id)->update([
                                'title'         => $title,
                                'description'   => $description,
                                'location'      => $location,
                                'group_id'      => $group_id
                            ]);

                        } else {
                            // If event had a parent_id, but the checkbox was unchecked, unlink event from parent
                            $event->parent_id   = null;
                            $event->title       = $title;
                            $event->description = $description;
                            $event->location    = $location;
                            $event->group_id    = $group_id;
                            $event->save();
                        }

                        return Response::view('calendar.index');

                    } else {

                        if(!$start_date) {
                            $validator->getMessageBag()->add(
                                'end',
                                Lang::get('validation.required', ['attribute ' => 'start-date '])
                            );

                            return Redirect::back()->withErrors($validator)->withInput();
                        } else {
                            $sd = new DateTime($start_date . ' ' . $start_time);

                            if($end_date == '') {
                                $end_date = $start_date;
                            }
                            $ed = new DateTime($end_date . ' ' . $end_time);

                            // Check if end date is before start date, if so, return with error
                            if($sd >= $ed) {

                                $validator->getMessageBag()->add(
                                    'end',
                                    Lang::get('validation.after', ['attribute ' => 'end-date ', 'date' => Input::get('start-date')])
                                );

                                // Redirect back with inputs and validator instance
                                return Redirect::back()->withErrors($validator)->withInput();

                            } else {
                                $event->title       = $title;
                                $event->description = $description;
                                $event->location    = $location;
                                $event->group_id    = $group_id;
                                $event->start_date  = $sd;
                                $event->end_date    = $ed;

                                $event->update();
                                return Redirect::route('calendar.index');
                            }
                        }
                    }
                /*
                    // If validator succeeds, prepare event object to be updated in the database
                    $event->title       = e(Input::get('title'));
                    $event->description = e(Input::get('description'));
                    $event->start_date  = new DateTime(Input::get('start'));

                    if (Input::get('day')) {
                        $event->allday = true;

                    } else {
                        $event->allday = false;
                        // If end-date is blank (____/__/__...), or if it's the same as the start-date/time,
                        // then end-date = start-date + 1h
                        if ((Input::get('end') == '____/__/__ __:__' || Input::get('end') == Input::get('start'))) {
                            $event->end_date = new DateTime(Input::get('start'));
                            $event->end_date->add(new DateInterval('PT1H'));

                        } elseif (new DateTime(Input::get('start')) >= new DateTime(Input::get('end'))) {
                            // Add an error message in the message collection (MessageBag instance)
                            $validator->getMessageBag()->add(
                                'end',
                                Lang::get('validation.after', ['attribute ' => 'end ', 'date' => Input::get('start')])
                            );

                            // Redirect back with inputs and validator instance
                            return Redirect::back()->withErrors($validator)->withInput();

                        } else {
                            $event->end_date = new DateTime(Input::get('end'));
                        }
                    }

                    // Recurring events handling
                    if (Input::get('repeat') && Input::get('nr_repeat')) {
                        $event->nr_repeat   = Input::get('nr_repeat');
                        $event->repeat_type = e(Input::get('repeat_type'));
                        $event->repeat_freq = Input::get('repeat_freq');
                    } else {
                        $event->nr_repeat   = null;
                        $event->repeat_type = null;
                        $event->repeat_freq = null;
                    }

                    $event->group_id = Input::get('group');
                    $event->save();

                    return Redirect::route('calendar.index');
                */}
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Response::view('calendar.index', [], 401);
            }
        } else {
            return Response::view('landing', [], 401);
        }
    }


    /**
     * Remove the specified appointment from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Sentry::check()) {
            // Find active user
            $user  = Sentry::getUser();
            $event = Appointment::find($id);

            // Check if User belongs to group/school which the appointment is from
            if ($user->hasAccess('school') || ($user->hasAccess('event') && $user->school_id == $event->group->school_id)) {
                $event->delete();
                return View::make('calendar.index');
            } else {
                // If no permissions, redirect the user to the calendar index page
                return Response::view('calendar.index', [], 401);
            }
        } else {
            return Response::view('landing', [], 401);
        }
    }

    public function validateDate($date)
    {
        $d = DateTime::createFromFormat('m/d/Y', $date);
        return $d && $d->format('m/d/Y') == $date;
    }
}
