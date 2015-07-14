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
        dd($sentry_user);
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
