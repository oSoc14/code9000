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

        foreach ($tables as $name) {
            //if you don't want to truncate migrations
            if (in_array($name[$keyName], $excepts))
                continue;

                  print($name[$keyName]."\n");
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
            $o->save();
            if ($i == 3) {
                $admin_user->calendars()->attach($o);
            }
            // Add year calendars
            for ($j=1; $j <= 6; $j++) {
                $color = randomColor('j' . $j . $o->school_id);
                $y = new Calendar();
                $y->name = $j . (($j == 1) ? 'ste' : 'de') . ' jaar';
                $y->slug = 'j' . $j;
                $y->description = 'events voor kindjes die even oud zijn';
                $y->color = $color;
                $y->school_id = $o->school_id;
                $y->parent_id = $o->id;
                $y->save();

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
                for ($k=0; $k < 3; $k++) {
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

        for ($i = 1; $i < 100; $i++) {
            $app = new Appointment();

            $app->title ='School event ' . $i;
            $app->description = 'we are going to test ' . rand() . ' something';
            $app->location = 'Gent';
            $app->calendar_id = $i % 74 + 1;
            $date = date('Y-m-d', strtotime( '+'.mt_rand(-10,30).' days'));
            $app->start = new DateTime($date . ' 13:00');
            $app->end = new DateTime($date . ' 16:00');
            $app->save();
        }
    }
}


define( "COL_MIN_AVG", 64 );
define( "COL_MAX_AVG", 192 );
define( "COL_STEP", 16 );

// (192 - 64) / 16 = 8
// 8 ^ 3 = 512 colors

function randomColor( $username ) {
        $range = COL_MAX_AVG - COL_MIN_AVG;
        $factor = $range / 256;
        $offset = COL_MIN_AVG;

        $base_hash = substr(md5($username), 0, 6);
        $b_R = hexdec(substr($base_hash,0,2));
        $b_G = hexdec(substr($base_hash,2,2));
        $b_B = hexdec(substr($base_hash,4,2));

        $f_R = floor((floor($b_R * $factor) + $offset) / COL_STEP) * COL_STEP;
        $f_G = floor((floor($b_G * $factor) + $offset) / COL_STEP) * COL_STEP;
        $f_B = floor((floor($b_B * $factor) + $offset) / COL_STEP) * COL_STEP;

        return sprintf('#%02x%02x%02x', $f_R, $f_G, $f_B);
}
