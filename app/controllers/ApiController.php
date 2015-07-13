<?php

/**
 * Class ApiController
 * This controller provides all API methods
 */
class ApiController extends \BaseController
{


    /**
     * Return a listing of the organisations
     * @return jSon Response with appointments
     */
    public function orgs()
    {
        $schools = School::all();

        // Returns JSON response of the user
        return Response::json($schools)->setCallback(Input::get('callback'));
    }

    /**
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
     */
    public function orgEvents($id)
    {
        if (!Sentry::check()) {
            // User is not logged in, or is not activated
            return Redirect::route('landing');
        }
        // Gets all appointments from the school
        $user = Sentry::getUser();

        // Check if user is superAdmin
        if ($user->hasAccess('superadmin')) {
            $appointments = Appointment::get()->load('calendar.school')->toArray();

            // Returns JSON response of the user
            return Response::json($appointments)->setCallback(
                Input::get('callback')
            ); //return View::make('calendar.events');

        } else {
            // If user is not superAdmin, show calendars based on the school of the logged in user
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

    }

    /**
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
     */
    public function orgUsers($id)
    {
    }

    /**
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
     */
    public function orgCalendars($id)
    {
    }

}