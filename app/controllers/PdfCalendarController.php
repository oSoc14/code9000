<?php

/**
 * Class PdfCalendarController
 * This controller is the pdf counterpart to the IcalCalendarController. It has the exact same functionality, except it
 * renders a pdf file in the end with the help of the Dompdf plugin.
 */
class PdfCalendarController extends \BaseController
{


    /**
     * Find correct appointments depending on $school and $calendar
     * Additionally add the appointments from the "global" calendar
     * Process these appointments and render them to an .pdf file which will be returned for download
     *
     * @param  string $school_slug the school slug
     * @param  string $calendar_slug the calendar slug
     * @return pdf downloadable file
     *
     */

    // TODO: Decide if we will keep using a PDF export, or instead just make a printer friendly view
    public function index($school_slug, $calendar_slug)
    {
        $appointments = CalendarController::getAppointmentsBySlugs($school_slug, $calendar_slug);
        $calendar = CalendarController::getCalendar($school_slug, $calendar_slug);
        $school = School::getBySlug($school_slug);

        // Compose the PDF with the help of the Dompdf plugin
        $calendar = self::composePdf($appointments, $school->name, $calendar->name);

        PDF::load($calendar, 'A4', 'landscape')->download($school->name . ' - calendar');
    }

    // TODO: FIX THE SORTING
    public function composePdf($appointments, $school, $calendar)
    {
        // Pdf "header" and html
        $html = HTML::style('css/print.css')
            . '<h1>' . ucfirst(trans('educal.school')) . ': ' . $school . '</h1>'
            . '<h2>' . ucfirst(trans('educal.group')) . ': ' . $calendar . '</h2>'
            . '<table class="table table-striped">'
            . '<thead><tr>'
            . '<th>' . ucfirst(trans('educal.date')) . '</th>'
            . '<th>' . ucfirst(trans('educal.title')) . '</th>'
            . '<th>' . ucfirst(trans('educal.description')) . '</th>'
            . '</tr></thead><tbody>';

        // Make an empty array which will be filled with all appointments
        $listAppointments = [];

        // Set the limitations for which appointments to get
        $dsta = new DateTime();
        $dend = new DateTime();
        // In this case we set the limit to 1 month in the past until 1 month in the future
        // Too many events will make the rendering substantially slower (Dompdf "issue")
        $dsta->sub(new DateInterval("P1M"));
        $dend->add(new DateInterval("P1M"));

        // Loop through appointments and add them to the calendar.
        foreach ($appointments as $appointment) {

            // Create an $app object which represents a single appointment to be pushed in the $listAppointments array
            $app = [];
            $app['title'] = $appointment['attributes']['title'];
            $app['description'] = $appointment['attributes']['description'];
            $app['start'] = $appointment['attributes']['start'];
            $app['end'] = $appointment['attributes']['end'];
            $app['allday'] = $appointment['attributes']['allDay'];

            // Recurence option (e.g. New Year happens every year)
            // Set recurrence rule
            /* if ($appointment['attributes']['repeat_type']) {

                 $rep_freq = $appointment['attributes']['repeat_freq'];

                 // Create DateTime objects to be able to do math with days.
                 $dtStart = new DateTime($appointment['attributes']['start']);
                 $dtStart->format('d-m-Y H:i');
                 $dtEnd = new DateTime($appointment['attributes']['end']);
                 $dtEnd->format('d-m-Y H:i');

                 // Calculate recurring appointments
                 for ($i = 0; $i < $appointment['attributes']['nr_repeat']; $i++) {

                     // Format dates in readable strings
                     $app['start'] = $dtStart->format('d-m-Y H:i');
                     $app['end']   = $dtEnd->format('d-m-Y H:i');

                     // Extra dateTime field to sort the array by later
                     $app['dateTime']   = $dtStart->format('YmdHi');

                     // Set the limits for what appointments to get (1 month in past till 1 month in future)
                     // If the appointment falls outside of these limits, do not add it to the $listAppointments
                     if ($dtStart > $dsta && $dtStart < $dend) {
                         array_push($listAppointments, $app);
                     }

                     // Check the repeat type (day, week, month, year) and set the corresponding recurrence rule
                     switch ($appointment['attributes']['repeat_type']) {
                         case 'd':
                             $dtStart->add(new DateInterval('P' . $rep_freq . 'D'));
                             $dtEnd->add(new DateInterval('P' . $rep_freq . 'D'));
                             break;
                         case 'w':
                             $dtStart->add(new DateInterval('P' . $rep_freq . 'W'));
                             $dtEnd->add(new DateInterval('P' . $rep_freq . 'W'));
                             break;
                         case 'M':
                             $dtStart->add(new DateInterval('P' . $rep_freq . 'M'));
                             $dtEnd->add(new DateInterval('P' . $rep_freq . 'M'));
                             break;
                         case 'y':
                             $dtStart->add(new DateInterval('P' . $rep_freq . 'Y'));
                             $dtEnd->add(new DateInterval('P' . $rep_freq . 'Y'));
                             break;
                     }
                 }

             } else */
            {

                // If there is no recurrence rule, just format the start and enddate gotten from the database
                $dateString = new DateTime($appointment['attributes']['start']);
                $app['start'] = $dateString->format('d-m-Y H:i');
                // Extra dateTime field to sort the array by later
                $app['dateTime'] = $dateString->format('YmdHi');
                $dateString2 = new DateTime($appointment['attributes']['end']);
                $app['end'] = $dateString2->format('d-m-Y H:i');
                $da = new DateTime($appointment->start);

                // Set the limits for which appointments to get (1 month in past till 1 month in future)
                if ($da > $dsta && $da < $dend) {
                    // If appointment is within these limits, push it to the $listAppointments
                    array_push($listAppointments, $app);
                }
            }
        }
        // Sort $listAppointments by dateTime field
        $listAppointments = array_values(
            array_sort(
                $listAppointments,
                function ($value) {
                    return $value['dateTime'];
                }
            )
        );

        // Loop through $listAppointments and add these to the pdf html
        foreach ($listAppointments as $apps) {
            $html .= '<tr><td width="20%">' . ucfirst(trans('educal.starts')) . '  ' . $apps['start'];

            // Only show this if the end date is specified
            if ($apps['end']) {
                $html .= '<br>' . ucfirst(trans('educal.ends')) . ' ' . $apps['end'] . '</td>';
            }

            $html .= '</td>'
                . '<td width="20%">' . $apps['title'] . '</td>'
                . '<td width="60%">' . $apps['description'] . '</td></tr>';
        }

        // Here we render appointments
        $html .= '</tbody></table>'
            . '<small>generated by EduCal</small>';

        return ($html);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        // Find selected appointment and push it to an array with a single element which will be sent to the
        // composePdf function (in this controller as well)
        $appointment = Appointment::find($id);
        $school = $appointment->load('group.school');
        $appointments = [];
        array_push($appointments, $appointment);

        $calendar = self::composePdf($appointments, $school->name, $school->calendar->name);

        return PDF::load($calendar, 'A4', 'landscape')->download($school->name . ' - calendar');
    }

}
