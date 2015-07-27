<?php

/**
 * Class CalendarController
 * This controller handles the CRUD of calendars.
 */
class CalendarController extends \BaseController
{

    /**
     * Display a listing of the calendars.
     *
     * @return Response
     */
    public function index()
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }
        // Find active user and set default variables to null
        $user = Sentry::getUser();
        $calendars = null;

        // Check if user is superAdmin
        if ($user->hasAccess('superadmin')) {
            $calendars = Calendar::where('school_id', '<>', '')->get();
            $calendars = $calendars->load('school');

            // Return view with selected parameters
            return View::make('calendarManagement.listGroups')->with('groups', $calendars);

        } elseif ($user->hasAccess('editor')) {

            // Get school_id, by which we will search for related calendars
            $schoolId = $user->school_id;

            // Find all calendars with certain school_id
            $calendars = Calendar::where('school_id', '=', $schoolId)->get();
            $calendars = $calendars->load('school');

            // Return view with selected parameters
            return View::make('calendarManagement.listGroups')->with('groups', $calendars);

        } else {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect');
        }


    }


    /**
     * Add a user to the calendar of people who can edit this calendar.
     *
     * @param  int $user_id
     * @return Response
     */
    public function addUserToCalendar($user_id, $calendar_id)
    {

        if (!Sentry::check()) {
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }

        // Find active user and calendar information
        $user = Sentry::getUser();
        $calendar = Calendar::find($calendar_id);
        $selectedUser = Sentry::findUserById($user_id);

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == $calendar->school_id)) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect');
        }

        // The minimal permission level is editor,
        // so we don't have to check if the selected user has access to editing features

        $school = $calendar->school;
        if ($selectedUser->school_id != $school->id) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect')->withErrors("This user does not belong to this school!");
        }

        $user->calendars()->attach($calendar);
    }

    /**
     * Remove a user from the calendar of people who can edit this calendar.
     *
     * @param  int $user_id
     * @return Response
     */
    public function removeUserFromCalendar($user_id, $calendar_id)
    {

        if (!Sentry::check()) {
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }

        // Find active user and calendar information
        $user = Sentry::getUser();
        $calendar = Calendar::find($calendar_id);
        $selectedUser = Sentry::findUserById($user_id);

        // Permission checks
        if (!$user->hasAccess('superadmin') && !($user->hasAccess('admin') && $user->school_id == $calendar->school_id)) {
            // If no permissions, redirect the user to the calendar index page
            return Redirect::route('calendar.redirect');
        }

        // The minimal permission level is editor,
        // so we don't have to check if the selected user has access to editing features

        $user->calendars()->detach($calendar);
    }


    /**
     * Get a calendar based on school and calendar slug
     * @param string $school_slug the school slug
     * @param string $calendar_slug the calendar slug
     * @return Calendar
     */
    public static function getCalendar($school_slug, $calendar_slug)
    {
        $school = School::getBySlug($school_slug);

        return Calendar::where('school_id', $school->id)->where('slug', $calendar_slug)->firstOrFail();
    }

    /**
     * Get all appointments from a calendar, including parent calendars
     * @param string $school_slug the slug of the school
     * @param string $calendar_slugs the slug of the calendar
     * @return array all appointments
     */
    public static function getAppointmentsBySlugs($school_slug, $calendar_slugs)
    {
        $slugs = explode('/', $calendar_slugs);
        $appointments = [];

        foreach ($slugs as $slug) {

            try {

                $appointments += CalendarController::getAppointments(CalendarController::getCalendar($school_slug,
                    $slug));
            } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                // ignore invalid calendars
            }
        }

        return $appointments;
    }

    /**
     * Get all appointments from a calendar, including parent calendars
     * @param Calendar $calendar the calendar to get the appointments for
     * @return array all appointments
     */
    public static function getAppointments($calendar)
    {
        // Create an empty appointments array, which we will fill with appointments to render later
        $appointments = [];

        // TODO: Change calendar handling, base it off calendar and school ID
        // TODO: Add support for entire school export (all calendars)
        // Load appointments based on calendar, attach calendar information to appointment
        $calendar->load('appointments.calendar');

        // Set the limitations for which appointments to get
        $dsta = new DateTime();
        $dend = new DateTime();

        // TODO: Make this better (1 year static range isn't good)
        // In this case we set the limit to 1 year in the past until 1 year in the future
        $dsta->sub(new DateInterval("P1M"));
        $dend->add(new DateInterval("P1Y"));

        // TODO: fix code duplication!

        foreach ($calendar->appointments as $appointment) {
            $da = new DateTime($appointment->start_date);

            // Set the limits for what appointments to get (1y in past till 1y in future)
            // If the appointment is within the limits, add it to the $appointments array
            if ($da > $dsta && $da < $dend) {
                array_push($appointments, $appointment);
            }
        }

        // Add all parent calendars
        while ($calendar->parent_id > 0) {

            foreach ($calendar->appointments as $appointment) {
                $da = new DateTime($appointment->start_date);

                // Set the limits for what appointments to get (1y in past till 1y in future)
                // If the appointment is within the limits, add it to the $appointments array
                if ($da > $dsta && $da < $dend) {
                    array_push($appointments, $appointment);
                }
            }
            $calendar = $calendar::find($calendar->parent_id);
            $calendar->load('appointments');
        }

        return $appointments;
    }
}
