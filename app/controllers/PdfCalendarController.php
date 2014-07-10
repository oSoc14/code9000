<?php

class PdfCalendarController extends \BaseController {


    /**
     * Find correct appointments depending on $school and $group
     * Process these appointments and render them to an .pdf file which will be returned for download
     *
     * @param  string  $school
     * @param  string  $group
     * @return pdf downloadable file
     *
     */
    public function index($school, $group)
    {
        $appointments = [];
        // Load appointments based on group
        $selGroup = Group::where('name', $school.'_'.$group)->first();
        $selGroup->load('appointments');
        $schoolName = $selGroup->load('school');
        $schoolName = $schoolName->school->name;
        // Add global appointments, unless only global appointments are requested
        if($group != 'global') {
            $globalGroup = Group::where('name', $school.'_global')->first();
            $globalGroup->load('appointments');

            foreach($globalGroup->appointments as $appointment) {
                array_push($appointments, $appointment);
            }
        }
        // Push to array
        foreach($selGroup->appointments as $appointment) {
            array_push($appointments, $appointment);
        }

        $calendar = self::composePdf($appointments, $schoolName, $group);
        return PDF::load($calendar, 'A4', 'portrait')->show();
    }

    public function composePdf($appointments, $school, $group)
    {

        $html =     '<h1>'.$school.'</h1>'
                    .'<h2>'.$group.'</h2>'
                    .'<table>'
                    .'<thead><tr>'
                        .'<th>Date</th>'
                        .'<th>Title</th>'
                        .'<th>Description</th>'
                    .'</tr></thead><tbody>';
        $listAppointments = [];

        // Loop through appointments and add them to the calendar.
        foreach($appointments as $appointment) {
            $app = [];
            $app['title'] = $appointment['attributes']['title'];
            $app['description'] = $appointment['attributes']['description'];
            $app['start_date'] = $appointment['attributes']['start_date'];
            $app['end_date'] = $appointment['attributes']['end_date'];
            $app['allday'] = $appointment['attributes']['allday'];
            // Recurence option (e.g. New Year happens every year)
            // Set recurrence rule
            if($appointment['attributes']['repeat_type']) {
                $rep_freq = $appointment['attributes']['repeat_freq'];
                // Create DateTime objects to be able to do math with days.
                $dtStart = new DateTime($appointment['attributes']['start_date']);
                $dtEnd = new DateTime($appointment['attributes']['end_date']);;
                for($i=0;$i<$appointment['attributes']['nr_repeat'];$i++) {
                    $app['start_date'] = $dtStart->format('Y-m-d H:i');
                    $app['end_date'] = $dtEnd->format('Y-m-d H:i');
                    array_push($listAppointments, $app);
                    switch($appointment['attributes']['repeat_type']) {
                        case 'd':
                            $dtStart->add(new DateInterval('P'.$rep_freq.'D'));
                            $dtEnd->add(new DateInterval('P'.$rep_freq.'D'));
                            break;
                        case 'w':
                            $dtStart->add(new DateInterval('P'.$rep_freq.'W'));
                            $dtEnd->add(new DateInterval('P'.$rep_freq.'W'));
                            break;
                        case 'M':
                            $dtStart->add(new DateInterval('P'.$rep_freq.'M'));
                            $dtEnd->add(new DateInterval('P'.$rep_freq.'M'));
                            break;
                        case 'y':
                            $dtStart->add(new DateInterval('P'.$rep_freq.'Y'));
                            $dtEnd->add(new DateInterval('P'.$rep_freq.'Y'));
                            break;
                    }
                }
            } else {
                array_push($listAppointments, $app);
            }
        }
        $listAppointments = array_values(array_sort($listAppointments, function($value)
        {
            return $value['start_date'];
        }));
        foreach($listAppointments as $app) {
            $html .= '<tr><td>'.$app['start_date'].' - '.$app['end_date'].'</td>'
                        .'<td>'.$app['title'].'</td>'
                        .'<td>'.$app['description'].'</td></tr>';
        }
        return($html);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // Find selected appointment
        $appointment = Appointment::find($id);
        $school = $appointment->load('group.school');
        $appointments = [];
        array_push($appointments, $appointment);

        $calendar = self::composePdf($appointments, $school->group->school->name, $school->group->name);
        return PDF::load($calendar, 'A4', 'portrait')->show();
    }

}
