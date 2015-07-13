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

        $events = [];
        $calendar = Calendar::find($id);
        $calendar->load('appointments');
        // Loop through calendars to get all appointments

        foreach ($calendar->appointments as $event) {
            array_push($events, $event);
        }

        // Returns JSON response of the user
        return Response::json($events)->setCallback(Input::get('callback'));

    }

}