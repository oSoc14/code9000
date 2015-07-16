<?php

/**
 * Class ApiController
 * This controller provides all API methods
 */
class ApiController extends \BaseController
{

    /**
     * Get all events for logged in user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @deprecated
     */
    public function allUserEvents()
    {
        if (!Sentry::check()) {
            return;
        }
        $user = Sentry::getUser();
        $appointments = [];
        $user->load('school.calendars.appointments.calendar.school');
        // Loop through calendars to get all appointments
        foreach ($user->school->calendars as $calendar) {
            $calendar->url = route("api.orgCalendarEvents", [$calendar->id]);
            foreach ($calendar->appointments as $appointment) {
                array_push($appointments, $appointment);
            }
        }

        // Returns JSON response of the user
        return Response::json($appointments)->setCallback(Input::get('callback'));
    }

    /**
     * Return a listing of the organisations
     * @return jSon Response with appointments
     */
    public function orgs()
    {
        $allSchools = School::all();
        $schools = [];
        // Loop through calendars to get all appointments
        foreach ($allSchools as $school) {
            array_push($schools, $school);
        }

        // Returns JSON response of the user
        return Response::json($schools)->setCallback(Input::get('callback'));
    }

    /**
     * Return a listing of the events based on the organisation id
     * @param $id int the organisation ID
     * @return jSon Response with events
     */
    public function orgEvents($id)
    {

        $events = [];
        $school = School::find($id);
        $school->load('calendars.appointments');

        // Loop through calendars to get all appointments
        foreach ($school->calendars as $calendar) {
            $calendar->url = route("api.orgCalendarEvents", [$calendar->id]);
            foreach ($calendar->appointments as $event) {
                array_push($events, $event);
            }
        }

        // Returns JSON response of the user
        return Response::json($events)->setCallback(Input::get('callback'));

    }

    /**
     * Return a listing of the users based on the organisation id
     * @param $id int the organisation ID
     * @return jSon Response with users
     */
    public function orgUsers($id = 0)
    {
        if ($id == 0) {
            if (!Sentry::check()) {
                return ApiController::createApiAccessError('You have to be logged in, or provide an organisation ID');
            }
            $id = Sentry::getUser()->school->id;
        }

        $users = [];
        $orgUsers = User::where('school_id', $id)->get();

        // Loop through calendars to get all appointments
        foreach ($orgUsers as $user) {
            array_push($users, $user);
        }

        // Returns JSON response of the user
        return Response::json($users)->setCallback(Input::get('callback'));

    }

    /**
     * Return a listing of the calendars based on the logged in user.
     * @param $id int the organisation ID
     * @return jSon Response with calendars
     */
    public function orgCalendars($id = 0)
    {

        if ($id == 0) {
            if (!Sentry::check()) {
                return ApiController::createApiAccessError('You have to be logged in, or provide an organisation ID');
            }
            $id = Sentry::getUser()->school->id;
        }

        $calendars = [];
        $orgCalendars = Calendar::where('school_id', $id)->get();

        // Loop through calendars to get all appointments
        foreach ($orgCalendars as $calendar) {
            $calendar->url = route("api.orgCalendarEvents", [$calendar->id]);
            array_push($calendars, $calendar);
        }

        // Returns JSON response of the user
        return Response::json($calendars)->setCallback(Input::get('callback'));

    }

    /**
     * Return a listing of the events based on the calendar id
     * @param $id int the calendar ID
     * @return jSon Response with events
     */
    public function calendarWithEvents($id)
    {

        $calendar = Calendar::find($id);
        $calendar->load('appointments');

        // Returns JSON response of the user
        return Response::json($calendar)->setCallback(Input::get('callback'));

    }

    /**
     * Return a listing of the events based on the calendar id
     * @param $id int the calendar ID
     * @return jSon Response with events
     */
    public function calendarEvents($id)
    {

        if (Input::has("start") && Input::has("end")) {
            $appQuery = Appointment::where('calendar_id', $id)->where('start', '>=',
                Input::get('start') . ' 00:00:00')->where('start', '<=', Input::get('end') . ' 00:00:00')->get();
        } else {
            $appQuery = Appointment::where('calendar_id', $id)->get();
        }

        // Returns JSON response of the user
        return Response::json($appQuery)->setCallback(Input::get('callback'));

    }

    /**
     * Handle a calendar post to the API
     */
    public function handleAppointment()
    {
        if (Input::has('id')) {
            return $this->updateAppointment();
        } else {
            return $this->storeAppointment();
        }
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateAppointment($id = 0)
    {
        if ($id == 0) {
            $id = Input::get('id');
        }

        if (!Sentry::check()) {
            return ApiController::createApiAccessError('You have to login first');
        }

        // Find active user
        $user = Sentry::getUser();
        $event = Appointment::find($id);

        // Check if User belongs to calendar/school which the appointment is from
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('editor')
                && $user->school_id == $event->calendar->school_id)
        ) {
            // If no permissions, redirect the user to the calendar index page
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        $validator = Validator::make(
            [
                'calendar' => Input::get('calendar'),
                'description' => Input::get('description'),
                'start' => Input::get('start'),
                'end' => Input::get('end'),
                'title' => Input::get('title'),
                'day' => Input::get('day')
            ],
            [
                'calendar' => 'required',
                'start' => 'required|date_format:Y-m-d H:i',
                'end' => 'required|date_format:Y-m-d H:i',
                'title' => 'required'
            ]
        );
        if ($validator->fails()) {
            return ApiController::createApiValidationError($validator->errors());
        }

        $title = e(Input::get('title'));
        $description = e(Input::get('description'));
        $location = e(Input::get('location'));
        $calendar_id = Input::get('calendar');
        $start = e(Input::get('start'));
        $end = e(Input::get('end'));
        $parents = Input::get('par');
        // TODO: Handle All day events, or decide to remove it alltogether
        // If the event isn't the whole day, determine the end date/time
        //$event->allday = false;
        // Handle datetime

        $sd = new DateTime($start);
        $ed = new DateTime($end);
        // Check if end date is before start date, if so, return with error
        if ($sd >= $ed) {
            $validator->getMessageBag()->add(
                'end',
                Lang::get('validation.after',
                    ['attribute ' => 'end ', 'date' => Input::get('start')])
            );

            // Redirect back with inputs and validator instance
            return ApiController::createApiValidationError($validator->errors());
        }

        // Recurring events handling
        if ($event->parent_id) {
            if ($parents) {
                $parent = Appointment::find($event->parent_id);
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
        $event->start = $sd;
        $event->end = $ed;
        $event->save();

        return ApiController::createApiOk('Changes saved');
    }

    /**
     * Store a new event
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function storeAppointment()
    {
        if (!Sentry::check()) {
            return ApiController::createApiAccessError('You have to login first');
        }
        // Find active user and set default variables to null
        $schools = null;
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAnyAccess(['admin', 'editor'])) {
            // If no permissions, redirect the user to the calendar index page
            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }

        $validator = Validator::make(
            [
                'calendar' => Input::get('calendar'),
                'description' => Input::get('description'),
                'start' => Input::get('start'),
                'end' => Input::get('end'),
                'title' => Input::get('title'),
                'day' => Input::get('day')
            ],
            [
                'calendar' => 'required',
                'start' => 'required|date_format:Y-m-d H:i',
                'end' => 'required|date_format:Y-m-d H:i',
                'title' => 'required'
            ]
        );

        // If validation fails, return to the create form with errors.
        if ($validator->fails()) {
            return ApiController::createApiValidationError($validator->errors());
        }
        $title = e(Input::get('title'));
        $description = e(Input::get('description'));
        $location = e(Input::get('location'));
        $calendar_id = Input::get('calendar');
        $start = e(Input::get('start'));
        $end = e(Input::get('end'));

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
                return ApiController::createApiValidationError($validator->errors());

            }
            // Loop through dates to validate them
            foreach ($dateArray as $da) {
                // If date is invalid, return error
                if (!self::validateDate($da)) {

                    $validator->getMessageBag()->add(
                        'end',
                        Lang::get('validation.date_format', ['attribute ' => 'Jaarkalender '])
                    );

                    // Redirect back with inputs and validator instance
                    return ApiController::createApiValidationError($validator->errors());
                }
            }
            // If all dates are validated and correct, create parent appointment and children
            $parent = new Appointment();
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
                $sd = new DateTime($start);
                $ed = new DateTime($end);
                $event->start = new DateTime($da . ' ' . date("H:i", $sd));
                $event->end = new DateTime($da . ' ' . date("H:i", $ed));
                $event->parent_id = $parent->id;
                $event->save();
            }

            return ApiController::createApiOk('Changes saved');
        }

        if (!$start) {
            $validator->getMessageBag()->add(
                'end',
                Lang::get('validation.required', ['attribute ' => 'start '])
            );

            return ApiController::createApiValidationError($validator->errors());
        }
        $sd = new DateTime($start);
        $ed = new DateTime($end);
        // Check if end date is before start date, if so, return with error
        if ($sd >= $ed) {

            $validator->getMessageBag()->add(
                'end',
                Lang::get('validation.after',
                    ['attribute ' => 'end ', 'date' => Input::get('start')])
            );

            return ApiController::createApiValidationError($validator->errors());

        }

        $event = new Appointment();
        $event->title = $title;
        $event->description = $description;
        $event->location = $location;
        $event->calendar_id = $calendar_id;
        $event->start = $sd;
        $event->end = $ed;
        $event->save();

        return ApiController::createApiOk('Changes saved');
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroyAppointment($id = 0)
    {
        if (!Sentry::check()) {
            return ApiController::createApiAccessError('You have to login first');
        }

        if ($id == 0) {
            $id = Input::get('id');
        }

        // Find active user
        $user = Sentry::getUser();
        $event = Appointment::find($id);

        // Check if User belongs to calendar/school which the appointment is from
        if ($user->hasAccess('superadmin') || ($user->hasAccess('editor') && $user->school_id == $event->calendar->school_id)) {
            $event->delete();

            return ApiController::createApiOk('Appointment deleted');
        }

        return ApiController::createApiAccessError('You do not have the right to perform this action');
    }

    /**
     * Handle a calendar post to the API
     */
    public function handleCalendar()
    {
        if (Input::has('id')) {
            $this->updateCalendar();
        } else {
            $this->storeCalendar();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storeCalendar()
    {
        if (!Sentry::check()) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You have to login first');
        }

        // Find active user
        $user = Sentry::getUser();

        if (!$user->hasAnyAccess(['admin', 'editor'])) {

            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }
        $school = null;

        // Validate input fields
        $validator = Validator::make(
            [
                'name' => e(Input::get('name')),
                'school' => Input::get('school'),
            ],
            [
                'name' => 'required',
                'school' => 'integer'
            ]
        );

        if ($validator->fails()) {
            return ApiController::createApiValidationError($validator->errors());
        }

        $calendar = new Calendar();
        $calendar->name = e(Input::get('name'));
        $calendar->description = e(Input::get('name'));
        $calendar->school_id = Input::get('school');

        $calendar->save();

        $user->calendars()->attach($calendar);

        return ApiController::createApiOk('Changes saved');

    }


    /**
     * Update the specified calendar in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateCalendar($id = 0)
    {

        if (!Sentry::check()) {
            // If no permissions, redirect to calendar index
            return ApiController::createApiAccessError('You have to login first');
        }

        if ($id == 0) {
            $id = Input::get('id');
        }

        // Find active user and calendar information
        $user = Sentry::getUser();
        $calendar = Calendar::find($id);

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == $calendar->school_id)) {
            // If no permissions, redirect the user to the calendar index page

            return ApiController::createApiAccessError('You do not have the right to perform this action');
        }
        // If permissions are met, get school info
        $school = $calendar->school;

        // Generate full calendar name
        if (Input::get('name') != null) {
            $calName = preg_replace('/[^A-Za-z0-9\-_ ]/', '', Input::get('name'));
        } else {
            $calName = $calendar->name;
        }

        // Make a validator to see if the new calendar name is unique if it's not the same as before
        // Validate input fields
        $validator = Validator::make(
            [
                'name' => e($calName),
                'school' => Input::get('school'),
                'permissions' => Input::get('permissions')
            ],
            [
                'school' => 'integer'
            ]
        );

        // Error handling
        if ($validator->fails()) {

            return ApiController::createApiValidationError($validator->errors());

            // TODO: take a look at "protected" calendars
        } elseif ($calName == $school->name || $calName == 'Administratie') {
            // Do not allow default calendars to be renamed

            return ApiController::createApiAccessError('You cannot rename this calendar');

        } else {

            $calendar->name = $calName;
            // Save/update the calendar
            $calendar->save();

            return ApiController::createApiOk('Changes saved');
        }
    }

    /**
     * Remove the specified calendar from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroyCalendar($id = 0)
    {
        if (!Sentry::check()) {
            return;
        }

        if ($id == 0) {
            $id = Input::get('id');
        }

        // Find active user and calendar information
        $user = Sentry::getUser();
        $calendar = Calendar::find($id);

        // Permission checks
        if ($user->hasAccess('superadmin') || ($user->hasAccess('admin') && $user->school_id == $calendar->school_id)) {
            $calendar->delete();
        }

    }

    private static function createApiValidationError($error)
    {
        return Response::json(array('succes' => false, 'type' => 'validation', 'feedback' => $error),
            400)->setCallback(Input::get('callback'));
    }

    private static function createApiAccessError($msg = '')
    {
        return Response::json(array('succes' => false, 'type' => 'access', 'feedback' => $msg),
            403)->setCallback(Input::get('callback'));
    }

    private static function createApiError($msg)
    {
        return Response::json(array('succes' => false, 'type' => 'generic', 'feedback' => $msg),
            400)->setCallback(Input::get('callback'));
    }

    private static function createApiOk($msg = '')
    {
        return Response::json(array('succes' => true, 'type' => '', 'feedback' => $msg),
            200)->setCallback(Input::get('callback'));
    }

    private static function validateDate($date)
    {
        $d = DateTime::createFromFormat('m/d/Y', $date);
        return $d && $d->format('m/d/Y') == $date;
    }
}