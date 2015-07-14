<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $school = new School();
        $school->name = "open Summer of code 2015";
        $school->city = "Gent";

        $school->save();

        // make sure the roles exist
        UserController::checkCreateRoles();
        // Find the role using the calendar id

        $adminRole = Sentry::findGroupByName('admin');


        $user2 = Sentry::createUser(
            [
                'email' => "a@a.a",
                'password' => "password",
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => "Foo",
                'last_name' => "Bar",
            ]
        );

        $editorRole = Sentry::findGroupByName('admin');

        // Assign the calendar to the user
        $user2->addGroup($editorRole);

        $calendar = new Calendar();
        $calendar->name = "global";
        $calendar->description = "events for everyone";
        $calendar->school_id = $school->id;

        $calendar->save();

        for ($i=0; $i < 200; $i++) {
            $app = new Appointment();

            $app->title ="School event " . $i;
            $app->description = "we're going to test " . rand() . " something";
            $app->location = "Gent";
            $app->calendar_id = $calendar->id;
            $date = date('Y-m-d', strtotime( '+'.mt_rand(-30,90).' days'));
            $app->start_date = new DateTime($date . ' 13:00');
            $app->end_date = new DateTime($date . ' 16:00');
            $app->save();
        }

        // link to global calendar
        $user2->calendars()->attach($calendar);

    }
}
