<?php

class SchoolTableSeeder extends Seeder {

    /**
     * Run the groupTable seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $school = new School();
        $school->name = 'TestSchool';
        $school->opening = '08:45';
        $school->city = 'Gent';
        $school->lang = 'nl';
        $school->save();

        Sentry::createGroup(
            [
                'name'        => $school->name . '__' . $school->id,
                'permissions' => [
                    'event'   => 1,
                ],
                'school_id'   => $school->id,
            ]
        );

        $group = Sentry::createGroup(
            [
                'name'        => 'Administratie__' . $school->id,
                'permissions' => [
                    'admin'   => 1,
                    'user'    => 1,
                    'group'   => 1,
                    'event'   => 1,
                ],
                'school_id'   => $school->id,
            ]
        );

        // Store the newly created user along with the school
        $user = Sentry::createUser(
            [
                'email'     => 'test@test.com',
                'password'  => 'testtest',
                'activated' => true,
                'school_id' => $school->id,
                'first_name'=> 'Test Principal',
                'last_name' => 'De directeur',
            ]
        );

        // Add the user to the admin group
        $user->addGroup($group);


        $school2 = new School();
        $school2->name = 'TrapHuis';
        $school2->opening = '09:30';
        $school2->city = 'Mariakerke';
        $school2->lang = 'nl';
        $school2->save();

        Sentry::createGroup(
            [
                'name'        => $school2->name . '__' . $school2->id,
                'permissions' => [
                    'event'   => 1,
                ],
                'school_id'   => $school2->id,
            ]
        );

        $group2 = Sentry::createGroup(
            [
                'name'        => 'Administratie__' . $school2->id,
                'permissions' => [
                    'admin'   => 1,
                    'user'    => 1,
                    'group'   => 1,
                    'event'   => 1,
                ],
                'school_id'   => $school2->id,
            ]
        );

        // Store the newly created user along with the school
        $user2 = Sentry::createUser(
            [
                'email'     => 'test@test.be',
                'password'  => 'testtest',
                'activated' => true,
                'school_id' => $school2->id,
                'first_name'=> 'Test Trap',
                'last_name' => 'De Trapper',
            ]
        );

        // Add the user to the admin group
        $user2->addGroup($group2);
    }

}
