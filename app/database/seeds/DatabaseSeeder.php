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

        DB::statement("SET foreign_key_checks=0");
        // set tables don't want to trucate here
        $excepts = ['migrations'];
        $tables = DB::connection()
            ->getPdo()
            ->query("SHOW FULL TABLES")
            ->fetchAll();
        $tableNames = [];

        $keys = array_keys($tables[0]);
        $keyName = $keys[0];
        $keyType = $keys[1];

        $colors = [
            1 => '#34ce45',
            2 => '#3a8bce',
            3 => '#c13838',
            4 => '#2cdbca',
            5 => '#e5c12c',
            6 => '#7548cc',
            7 => '#e08a00',
            8 => '#d66ae5',
        ];

        foreach ($tables as $name) {
            //if you don't want to truncate migrations
            if (in_array($name[$keyName], $excepts)) {
                continue;
            }

            print($name[$keyName] . "\n");
            DB::table($name[$keyName])->truncate();
        }
        DB::statement("SET foreign_key_checks=1");

        $school = new School();
        $school->name = "Don Bosco";
        $school->city = "Halle";
        $school->slug = "donbosco-halle";
        $school->save();

        $school = new School();
        $school->name = "Sint-Franciscusinstituut Basisschool";
        $school->city = "Kesselare";
        $school->slug = 'sfb-kesselare';
        $school->save();

        $school = new School();
        $school->name = "open Summer of code 2015";
        $school->city = "Gent";
        $school->slug = "osoc";
        $school->save();

        // make sure the roles exist
        UserController::checkCreateRoles();
        $superRole = Sentry::findGroupByName('superadmin');
        $adminRole = Sentry::findGroupByName('admin');
        $editorRole = Sentry::findGroupByName('editor');

        $super_user = Sentry::createUser(
            [
                'email' => 'super@osoc',
                'password' => 'password',
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => 'Foo',
                'last_name' => 'Bar',
            ]
        );

        $admin_user = Sentry::createUser(
            [
                'email' => 'admin@osoc',
                'password' => 'password',
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => 'Admin',
                'last_name' => 'Bar',
            ]
        );

        $editor_user = Sentry::createUser(
            [
                'email' => 'editor@osoc',
                'password' => 'password',
                'activated' => true,
                'school_id' => $school->id,
                'first_name' => 'Editor',
                'last_name' => 'Bar',
            ]
        );

        // Assign the calendar to the user
        $super_user->addGroup($superRole);
        $admin_user->addGroup($superRole);
        $editor_user->addGroup($superRole);

        $letter = ['A', 'B', 'C', 'D'];
        $uCount = 1001;

        // Add org calendars
        for ($i = 1; $i <= 3; $i++) {
            $o = new Calendar();
            $o->name = School::find($i)->slug;
            $o->description = 'events for everyone in school';
            $o->school_id = $i;
            $o->color = $colors[8];
            $o->save();

            $app = new Appointment();

            $app->title = 'School event ' . $i;
            $app->description = 'we are going to test ' . rand() . ' something';
            $app->location = 'Gent';
            $app->calendar_id = $o->id;
            $date = date('Y-m-d', strtotime('2015-07-01 + ' . mt_rand(0, 31) . ' days'));
            $app->start = new DateTime($date . ' 13:00');
            $app->end = new DateTime($date . ' 16:00');
            $app->save();

            if ($i == 3) {
                $admin_user->calendars()->attach($o);
            }
            // Add year calendars
            for ($j = 1; $j <= 6; $j++) {
                $color = $colors[$j];
                $y = new Calendar();
                $y->name = $j . (($j == 1) ? 'ste' : 'de') . ' jaar';
                $y->slug = 'j' . $j;
                $y->description = 'events voor kindjes die even oud zijn';
                $y->color = $color;
                $y->school_id = $o->school_id;
                $y->parent_id = $o->id;
                $y->save();
                if ($i == 3) {
                    $admin_user->calendars()->attach($y);
                }
                $app = new Appointment();

                $app->title = 'Year event ' . $i;
                $app->description = 'we are going to test ' . rand() . ' something';
                $app->location = 'Gent';
                $app->calendar_id = $y->id;
                $date = date('Y-m-d', strtotime('2015-07-01 + ' . mt_rand(0, 31) . ' days'));
                $app->start = new DateTime($date . ' 13:00');
                $app->end = new DateTime($date . ' 16:00');
                $app->save();

                $user = Sentry::createUser([
                    'email' => $j . '@' . School::find($i)->slug,
                    'password' => 'password',
                    'activated' => true,
                    'school_id' => $o->school_id,
                    'first_name' => 'Foo' . $uCount,
                    'last_name' => 'Bar' . $uCount,
                ]);
                $user->save();
                $uCount++;
                $user->addGroup($adminRole);
                $user->calendars()->attach($y);
                $user->calendars()->attach($o);
                $user->save();

                if ($i == 3) {
                    $admin_user->calendars()->attach($y);

                    if ($j == 4) {
                        $editor_user->calendars()->attach($y);
                    }
                }

                // Add class calendars
                for ($k = 0; $k < 3; $k++) {
                    $c = new Calendar();
                    $c->name = 'Klas ' . $j . $letter[$k];
                    $c->slug = $j . $letter[$k];
                    $c->description = 'events voor onze klas';
                    $c->color = $color;
                    $c->school_id = $o->school_id;
                    $c->parent_id = $y->id;
                    $c->save();

                    $user = Sentry::createUser([
                        'email' => $j . $letter[$k] . '@' . School::find($i)->slug,
                        'password' => 'password',
                        'activated' => true,
                        'school_id' => $o->school_id,
                        'first_name' => 'Foo' . $uCount,
                        'last_name' => 'Bar' . $uCount,
                    ]);

                    $user->save();
                    $uCount++;
                    $user->addGroup($editorRole);
                    $user->calendars()->attach($c);
                    $user->save();
                }
            }
        }

        for ($i = 1; $i < 200; $i++) {
            $app = new Appointment();

            $app->title = 'random event ' . $i;
            $app->description = 'we are going to test ' . rand() . ' something';
            $app->location = 'Gent';
            $app->calendar_id = $i % 74 + 1;
            $date = date('Y-m-d', strtotime('+' . mt_rand(-30, 30) . ' days'));
            $app->start = new DateTime($date . ' 13:00');
            $app->end = new DateTime($date . ' 16:00');
            $app->save();
        }
    }
}
