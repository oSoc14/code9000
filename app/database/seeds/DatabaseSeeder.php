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

        $user = Sentry::createUser(
            [
                'email' => "john.doe@example.com",
                'password' => "password",
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => "John",
                'last_name' => "Doe",
            ]
        );

        // make sure the roles exist
        UserController::checkCreateRoles();
        // Find the role using the calendar id

        $adminRole = Sentry::findGroupByName('admin');

        // Assign the calendar to the user
        $user->addGroup($adminRole);

        $user2 = Sentry::createUser(
            [
                'email' => "foo.bar@example.com",
                'password' => "password",
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => "Foo",
                'last_name' => "Bar",
            ]
        );

        $editorRole = Sentry::findGroupByName('editor');

        // Assign the calendar to the user
        $user2->addGroup($editorRole);

        $calendar = new Calendar();
        $calendar->name = "global";
        $calendar->description = "events for everyone";
        $calendar->school_id = $school->id;

        $calendar->save();

        $app = new Appointment();

        $app->title = "test event";
        $app->description = "we're going to test something";
        $app->location = "Gent";
        $app->calendar_id = $calendar->id;
        $app->start_date = new DateTime("14-07-2015" . ' ' . "10:00");
        $app->end_date = new DateTime("14-07-2015" . ' ' . "22:00");
        $app->save();

        // link to global calendar
        $user->calendars()->attach($calendar);

    }
}
