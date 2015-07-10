<?php

/**
 * Class CalendarViewController
 * This controller is the main controller of the application. It handles the CRUD of all events.
 */
class CalendarViewController extends \BaseController
{

    /**
     * Display a calendar view to logged in user
     * @return Response
     */
    public function index()
    {
        if (!Sentry::check()) {
            // User is not logged in, or is not activated
            return Redirect::route('landing');
        }

        return View::make('calendar.index');
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return Response
     */
    public function create()
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }
        // Find active user and set default variables to null
        $user = Sentry::getUser();
        $calendars = null;
        $schoolName = null;

        // Permission checks
        if (!$user->hasAnyAccess(['admin', 'editor'])) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }

        // If user is a superAdmin, show all possible calendars to add an event to
        if ($user->hasAccess(['school'])) {
            $calendars = Calendar::where('school_id', '<>', '')->get();
            $opening = '';
        } else {
            // If the user isn't a superAdmin, only show the calendars to which the user has permissions
            $user->load('school.calendars.appointments');
            $calendars = $user->calendars;
            $opening = $user->school->opening;
        }

        // Transform recieved objectList (from database) into array to send with view
        $smartcalendar = [];
        foreach ($calendars as $calendar) {
            $smartcalendar[$calendar->id] = $calendar->name;
        }

        // Show the form where users can add appointments
        // TODO: refactor view
        return View::make('calendar.create')->with('groups', $smartcalendar)->with('opening', $opening);


    }

    /**
     * Store a newly created appointment in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }
        // Find active user and set default variables to null
        $schools = null;
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAnyAccess(['admin', 'editor'])) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }


        $endDate = new DateTime();
        // Check if endDate isn't blank
        if (Input::get('end-date') == '') {
            $endDate = null;
        }

        $validator = Validator::make(
            [
                'calendar' => Input::get('calendar'),
                'description' => Input::get('description'),
                'start-date' => Input::get('start-date'),
                'end-date' => $endDate,
                'start-time' => Input::get('start-time'),
                'end-time' => Input::get('end-time'),
                'title' => Input::get('title'),
                'day' => Input::get('day')
            ],
            [
                'calendar' => 'required',
                'description' => 'required',
                'start-date' => 'date',
                'end-date' => 'date',
                'start-time' => 'required|date_format:H:i',
                'end-time' => 'required|date_format:H:i',
                'title' => 'required'
            ]
        );

        // If validation fails, return to the create form with errors.
        if ($validator->fails()) {
            return Redirect::route('event.create')->withInput()->withErrors($validator);
        } else {
            $title = e(Input::get('title'));
            $description = e(Input::get('description'));
            $location = e(Input::get('location'));
            $calendar_id = Input::get('calendar'); // TODO: REFACTORED INPUT! CHANGE IN VIEW
            $start_date = e(Input::get('start-date'));
            $end_date = e(Input::get('end-date'));
            $start_time = e(Input::get('start-time'));
            $end_time = e(Input::get('end-time'));

            // TODO: Handle All day events, or decide to remove it alltogether
            // If the event isn't the whole day, determine the end date/time
            //$event->allday = false;

            // Recurring events handling
            if (Input::get('repeat')) {

                $dateArray = explode(',', e(Input::get('repeat-dates')));
                // Check if there are any dates selected, return error if not
                if (count($dateArray) == 0) {

                    $validator->getMessageBag()->add(
                        'end',
                        Lang::get('validation.countmin', ['attribute ' => 'Jaarkalender ', 'min' => '1'])
                    );

                    // Redirect back with inputs and validator instance
                    return Redirect::back()->withErrors($validator)->withInput();

                } else {
                    // Loop through dates to validate them
                    foreach ($dateArray as $da) {
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
                    $parent = new AppParent();
                    $parent->title = $title;
                    $parent->description = $description;
                    $parent->location = $location;
                    $parent->calendar_id = $calendar_id;
                    $parent->save();

                    foreach ($dateArray as $da) {
                        $event = new Appointment();
                        $event->title = $title;
                        $event->description = $description;
                        $event->location = $location;
                        $event->calendar_id = $calendar_id;
                        $event->start_date = new DateTime($da . ' ' . $start_time);
                        $event->end_date = new DateTime($da . ' ' . $end_time);
                        $event->parent_id = $parent->id;
                        $event->save();
                    }
                }

                return Redirect::route('calendar.index');
            } else {

                if (!$start_date) {
                    $validator->getMessageBag()->add(
                        'end',
                        Lang::get('validation.required', ['attribute ' => 'start-date '])
                    );

                    return Redirect::back()->withErrors($validator)->withInput();
                } else {
                    $sd = new DateTime($start_date . ' ' . $start_time);

                    if ($end_date == '') {
                        $end_date = $start_date;
                    }
                    $ed = new DateTime($end_date . ' ' . $end_time);

                    // Check if end date is before start date, if so, return with error
                    if ($sd >= $ed) {

                        $validator->getMessageBag()->add(
                            'end',
                            Lang::get('validation.after',
                                ['attribute ' => 'end-date ', 'date' => Input::get('start-date')])
                        );

                        // Redirect back with inputs and validator instance
                        return Redirect::back()->withErrors($validator)->withInput();

                    } else {
                        $event = new Appointment();
                        $event->title = $title;
                        $event->description = $description;
                        $event->location = $location;
                        $event->calendar_id = $calendar_id;
                        $event->start_date = $sd;
                        $event->end_date = $ed;
                        $event->save();

                        return Redirect::route('calendar.index');
                    }
                }
            }
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
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }
        // Find active user
        $user = Sentry::getUser();

        // Check permissions
        if (!$user->hasAnyAccess(['school', 'editor'])) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }
        $event = Appointment::find($id);

        // Check if user is superAdmin
        if ($user->hasAccess(['school'])) {
            $calendars = Calendar::where('school_id', '<>', '')->get();

        } elseif ($user->school_id == $event->calendar->school_id) {
            // Check if User belongs to calendar/school which the appointment is from
            $user->load('school.calendars.appointments');
            $calendars = $user->school->calendars;

        } else {
            return Redirect::route('calendar.index');
        }

        // Make a list of all the calendars in a school to show with the view
        $smartcal = [];
        foreach ($calendars as $calendar) {
            $smartcal[$calendar->id] = $calendar->name;
        }

        $event = Appointment::find($id);

        return View::make('calendar.edit')->with('groups', $smartcal)->with('event', $event);

    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }
        // Find active user
        $user = Sentry::getUser();
        $event = Appointment::find($id);

        // Check if User belongs to calendar/school which the appointment is from
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('editor')
                && $user->school_id == $event->calendar->school_id)
        ) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.index');
        }
        $endDate = new DateTime();
        // Check if endDate isn't blank
        if (Input::get('end-date') == '') {
            $endDate = null;
        }

        $validator = Validator::make(
            [
                'calendar' => Input::get('calendar'),
                'description' => Input::get('description'),
                'start-date' => Input::get('start-date'),
                'end-date' => $endDate,
                'start-time' => Input::get('start-time'),
                'end-time' => Input::get('end-time'),
                'title' => Input::get('title'),
                'day' => Input::get('day')
            ],
            [
                'calendar' => 'required',
                'description' => 'required',
                'start-date' => 'date',
                'end-date' => 'date',
                'start-time' => 'required|date_format:H:i',
                'end-time' => 'required|date_format:H:i',
                'title' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Redirect::route('event.edit', $id)->withInput()->withErrors($validator);
        } else {
            $title = e(Input::get('title'));
            $description = e(Input::get('description'));
            $location = e(Input::get('location'));
            $calendar_id = Input::get('calendar'); // TODO: REFACTORED INPUT, REFACTOR VIEW!
            $start_date = e(Input::get('start-date'));
            $end_date = e(Input::get('end-date'));
            $start_time = e(Input::get('start-time'));
            $end_time = e(Input::get('end-time'));
            $parents = Input::get('par');

            // TODO: Handle All day events, or decide to remove it alltogether
            // TODO: Update date/time if needed
            // If the event isn't the whole day, determine the end date/time
            //$event->allday = false;

            // Handle datetime
            if (!$start_date) {
                $validator->getMessageBag()->add(
                    'end',
                    Lang::get('validation.required', ['attribute ' => 'start-date '])
                );

                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $sd = new DateTime($start_date . ' ' . $start_time);

                if ($end_date == '') {
                    $end_date = $start_date;
                }
                $ed = new DateTime($end_date . ' ' . $end_time);

                // Check if end date is before start date, if so, return with error
                if ($sd >= $ed) {

                    $validator->getMessageBag()->add(
                        'end',
                        Lang::get('validation.after',
                            ['attribute ' => 'end-date ', 'date' => Input::get('start-date')])
                    );

                    // Redirect back with inputs and validator instance
                    return Redirect::back()->withErrors($validator)->withInput();
                }
            }

            // Recurring events handling
            if ($event->parent_id) {
                if ($parents) {
                    $parent = AppParent::find($event->parent_id);
                    // Update parent event
                    $parent->title = $title;
                    $parent->description = $description;
                    $parent->location = $location;
                    $parent->calendar_id = $calendar_id;
                    $parent->save();

                    Appointment::where('parent_id', $parent->id)->update([
                        'title' => $title,
                        'description' => $description,
                        'location' => $location,
                        'calendar_id' => $calendar_id
                    ]);
                } else {
                    // If event had a parent_id, but the checkbox was unchecked, unlink event from parent
                    $event->parent_id = null;
                }
            }

            $event->title = $title;
            $event->description = $description;
            $event->location = $location;
            $event->calendar_id = $calendar_id;
            $event->start_date = $sd;
            $event->end_date = $ed;
            $event->save();

            return Redirect::route('calendar.index');
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
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }
        // Find active user
        $user = Sentry::getUser();
        $event = Appointment::find($id);

        // Check if User belongs to calendar/school which the appointment is from
        if ($user->hasAccess('superadmin') || ($user->hasAccess('editor') && $user->school_id == $event->calendar->school_id)) {
            $event->delete();
        }

        return Redirect::route('calendar.index');
    }

    public function validateDate($date)
    {
        $d = DateTime::createFromFormat('m/d/Y', $date);

        return $d && $d->format('m/d/Y') == $date;
    }
}
