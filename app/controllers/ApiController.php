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
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
     */
    public function orgEvents($id)
    {

        $calendars = [];
        $school = School::find($id);
        $school->load('calendars');

        // Loop through calendars to get all appointments
        foreach ($school->calendars as $calendar) {
            array_push($calendars, $calendar);
        }

        // Returns JSON response of the user
        return Response::json($calendars)->setCallback(Input::get('callback'));

    }

    /**
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
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
     * Return a listing of the events based on the logged in user.
     * @return jSon Response with appointments
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

}