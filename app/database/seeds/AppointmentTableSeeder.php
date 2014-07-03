<?php

class AppointmentTableSeeder extends Seeder {

    /**
     * Run the groupTable seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $startdate = new DateTime();
        Appointment::create(array(
            'title'        => 'EduCal Setup',
            'description'  => 'Install a new instance of EduCal',
            'start_date'   => $startdate,
            'end_date'     => $startdate->add(new DateInterval('PT1H')),
            'group_id'     => 2
        ));
    }

}
