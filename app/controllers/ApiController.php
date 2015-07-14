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
    public function orgUsers($id)
    {
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
    public function orgCalendars($id)
    {
        $calendars = [];
        $orgCalendars = Calendar::where('school_id', $id)->get();

        // Loop through calendars to get all appointments
        foreach ($orgCalendars as $calendar) {
            array_push($calendars, $calendar);
        }

        // Returns JSON response of the user
        return Response::json($calendars)->setCallback(Input::get('callback'));

    }

    /**
     * Return a listing of the events based on the organisation id
     * @param $id int the calendar ID
     * @return jSon Response with events
     */
    public function calendarEvents($id)
    {

        $calendar = Calendar::find($id);
        $calendar->load('appointments');

        // Returns JSON response of the user
        return Response::json($calendar)->setCallback(Input::get('callback'));

    }

    public function storeEvent()
    {
        if (!Sentry::check()) {
            return ApiController::createApiAccessError('You have to login first');;
        }
        // Find active user and set default variables to null
        $schools = null;
        $user = Sentry::getUser();

        // Permission checks
        if (!$user->hasAnyAccess(['admin', 'editor'])) {
            // If no permissions, redirect the user to the calendar index page
            return ApiController::createApiAccessError('You do not have the right to perform this action');
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
            return ApiController::createApiValidationError($validator->errors());
        }
        $title = e(Input::get('title'));
        $description = e(Input::get('description'));
        $location = e(Input::get('location'));
        $calendar_id = Input::get('calendar');
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

            return ApiController::createApiOk('Changes saved');
        }

        if (!$start_date) {
            $validator->getMessageBag()->add(
                'end',
                Lang::get('validation.required', ['attribute ' => 'start-date '])
            );

            return ApiController::createApiValidationError($validator->errors());
        }
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

            return ApiController::createApiValidationError($validator->errors());

        }

        $event = new Appointment();
        $event->title = $title;
        $event->description = $description;
        $event->location = $location;
        $event->calendar_id = $calendar_id;
        $event->start_date = $sd;
        $event->end_date = $ed;
        $event->save();

        return ApiController::createApiOk('Changes saved');
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
}