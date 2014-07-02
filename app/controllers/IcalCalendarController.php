<?php

class IcalCalendarController extends \BaseController {

	/**
	 * Find correct appointments depending on $school and $class
     * Process these appointments and render them to an .ics file which will be returned for download
	 *
     * @param  string  $school
     * @param  string  $class
	 * @return Cal.ics download file
     *
     * TODO: Implement $class functionality (so that the function takes $class into account)
	 */
	public function index($school, $class)
	{
        // Find school
        $schools = School::where('name', $school)->get();
        $schools->load('groups.appointments');

        $appointments = [];
        foreach($schools as $schl) {
            foreach($schl->groups as $group) {

                // TODO: if $group['name'] == $class...

                foreach($group->appointments as $appointment) {
                    array_push($appointments, $appointment);
                }
            }
        }
        //var_dump($appointments);

        // Set default timezone (PHP 5.4)
        date_default_timezone_set('Europe/Berlin');

        // 1. Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar('www.example.com');

        // Loop through appointments and add them to the calendar.
        foreach($appointments as $appointment) {

            // 2. Create an event
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent->setSummary($appointment['attributes']['title']);
            $vEvent->setDescription($appointment['attributes']['description']);
            $vEvent->setDtStart(new \DateTime($appointment['attributes']['start_date']));
            $vEvent->setDtEnd(new \DateTime($appointment['attributes']['end_date']));
            $vEvent->setNoTime(true);
            $vEvent->setStatus('TENTATIVE');
            
            // Recurence option (e.g. New Year happens every year)
            /*
            // Set recurrence rule
            $recurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
            $recurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_YEARLY);
            $recurrenceRule->setInterval(1);
            $vEvent->setRecurrenceRule($recurrenceRule);
            */

            // Adding Timezone (optional)
            $vEvent->setUseTimezone(true);
            $vEvent->setTimeTransparency('TRANSPARENT');

            // 3. Add event to calendar
            $vCalendar->addEvent($vEvent);
        }

        // 4. Set headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        // 5. Output
        return $vCalendar->render();


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
		//
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
