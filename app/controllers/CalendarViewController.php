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
        $user = Sentry::getUser();

        $uid = 0;
        if ($logged) {
            $uid == $user->getId();
        }

        $write = false;
        $admin = false;
        if ($logged && $user->school_id == $school->id) {
            $write = true;
            if (Sentry::getUser()->hasAccess('admin')) {
                $admin = true;
            }
        }
        $calendars = [];
        $orgCalendars = Calendar::where('school_id', $school->id)->get();
        // Loop through calendars to get all appointments
        foreach ($orgCalendars as $calendar) {
            $calendar->url = route("api.orgCalendarEvents", [$calendar->id]);
            $calendar->load("appointments");
            $calendars[$calendar->id] = $calendar;
            if(!$calendar->parent_id) $root = $calendar;
        }

        $userCalendars = [];
        if ($logged) {
            foreach ($user->calendars as $calendar) {
                $userCalendars[$calendar->id] = $calendar->name;
            }
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
            "root" => $root,
            "calendars" => json_encode($calendars),
            "calendars_raw" => $calendars,
            "editableCalendars" => $userCalendars,
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
