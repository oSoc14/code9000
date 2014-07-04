<?php

class IcalCalendarController extends \BaseController {

	/**
	 * Find correct appointments depending on $school and $group
     * Process these appointments and render them to an .ics file which will be returned for download
	 *
     * @param  string  $school
     * @param  string  $group
	 * @return Cal.ics download file
     *
     * TODO: Implement $school functionality, meaning we have to find all general events for a school that overlaps every class.
	 */
	public function index($school, $group)
	{
        // Find school
        //$selSchool = School::where('short', $school)->first();
        //$selSchool->load('groups.appointments');

        // Find group
        $selGroup = Group::where('name', $school.'_'.$group)->first();
        $selGroup->load('appointments');

        $appointments = [];
        foreach($selGroup->appointments as $appointment) {
            array_push($appointments, $appointment);
        }

        //var_dump($appointments);
        $calendar = self::composeIcal($appointments);
        return $calendar->render();

	}

    public function composeIcal($appointments)
    {
        // Set default timezone (PHP 5.4)
        date_default_timezone_set('Europe/Berlin');

        // 1. Create new calendar
        $calendar = new \Eluceo\iCal\Component\Calendar('www.example.com');

        // Loop through appointments and add them to the calendar.
        foreach($appointments as $appointment) {

            // 2. Create an event
            $event = new \Eluceo\iCal\Component\Event();
            $event->setSummary($appointment['attributes']['title']);
            $event->setDescription($appointment['attributes']['description']);
            $event->setDtStart(new \DateTime($appointment['attributes']['start_date']));
            $event->setDtEnd(new \DateTime($appointment['attributes']['end_date']));
            $event->setNoTime(true);
            $event->setStatus('TENTATIVE');

            // Recurence option (e.g. New Year happens every year)
            /*
            // Set recurrence rule
            $recurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
            $recurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_YEARLY);
            $recurrenceRule->setInterval(1);
            $vEvent->setRecurrenceRule($recurrenceRule);
            */

            // Adding Timezone (optional)
            $event->setUseTimezone(true);
            $event->setTimeTransparency('TRANSPARENT');

            // 3. Add event to calendar
            $calendar->addEvent($event);
        }

        // 4. Set headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        // 5. Output
        return $calendar;
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
        $appointments = [];
        array_push($appointments, $appointment);

        //var_dump($appointments);
        $calendar = self::composeIcal($appointments);
        return $calendar->render();
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
