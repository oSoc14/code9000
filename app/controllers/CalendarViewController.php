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
    public function index($school_slug)
    {
        if ($school_slug == null || $school_slug == '') {
            if (Sentry::check()) {
                $school = Sentry::getUser()->school;
            } else {
                // User is not logged in, and no slug for readonly access provided
                return Redirect::route('landing');
            }
        } else {
            $school = SchoolController::getSchoolBySlug($school_slug);
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
        ]);
    }

}
