<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function index($school_slug)
    {

        try {
            $school = SchoolController::getSchoolBySlug($school_slug);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('landing')->withErrors("School not found!");
        }


        $logged = Sentry::check();
        $sentry_user = Sentry::getUser();

        $uid = 0;
        if ($logged) {
            $uid == $sentry_user->id;
        }

        $write = false;
        $admin = false;
        if ($logged && $sentry_user->school_id == $school->id) {
            $write = true;
            if ($sentry_user->hasAccess('admin')) {
                $admin = true;
            }
        }

        $calendars = [];
        $orgCalendars = Calendar::where('school_id', $school->id)->get();
        // Loop through calendars to get all appointments
        foreach ($orgCalendars as $calendar) {
            $calendar->url = route("api.orgCalendarEvents", [$calendar->id]);
            $calendar->load("appointments");
            array_push($calendars, $calendar);
        }


        return View::make('calendar.index')->with([
            "school" => json_encode($school),
            "user" => json_encode([
                "id" => $uid,
                "logged_in" => $logged,
                "permissions" => [
                    "editor" => $write,
                    "admin" => $admin,
                ]
            ]),
            "org" => $school,
            "calendars" => json_encode($calendars),
        ]);
    }

    public function goToCalendar()
    {
        if (!Sentry::check()) {
            return Redirect::route('landing');
        }

        return Redirect::route("orgs.index", [Sentry::getUser()->school->slug]);
    }

}
