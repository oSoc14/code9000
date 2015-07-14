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
        $school->name = "Sint-Janscollege";
        $school->city = "Sint-Amandsberg";
        $school->slug = "sjc-gent";
        $school->save();

        $school = new School();
        $school->name = "Don Bosco";
        $school->city = "Halle";
        $school->slug = "donbosco-halle";
        $school->save();

        $school = new School();
        $school->name = "Sint-Franciscusinstituut Basisschool";
        $school->city = "Kesselare";
        $school->slug = "sfbk-kesselare";
        $school->save();

        $school = new School();
        $school->name = "open Summer of code 2015";
        $school->city = "Gent";
        $school->slug = "osoc";
        $school->save();

        // make sure the roles exist
        UserController::checkCreateRoles();

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

        $superRole = Sentry::findGroupByName('superadmin');
        $adminRole = Sentry::findGroupByName('admin');
        $editorRole = Sentry::findGroupByName('editor');

        // Assign the calendar to the user
        $user2->addGroup($superRole);

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

        for ($i=0; $i < 200; $i++) {
            $user = Sentry::createUser(
                [
                    'email' => $i . "@a.a",
                    'password' => "password",
                    'activated' => true,
                    'school_id' => $i%4 + 1,
                    'first_name' => "Foo" . $i,
                    'last_name' => "Bar" . $i,
                ]
            );
            $user->addGroup($editorRole);
            if($i%15){
                $user->addGroup($adminRole);
            }
        }


    }
}
