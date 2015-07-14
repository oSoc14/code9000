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
    public function index()
    {
        if (!Sentry::check()) {
            // User is not logged in, or is not activated
            return Redirect::route('landing');
        }

        return View::make('calendar.index');
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
