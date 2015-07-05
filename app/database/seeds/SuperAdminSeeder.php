<?php

class SuperAdminSeeder extends Seeder {

    /**
     * Run the groupTable seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        Sentry::createGroup(array(
            'name'        => 'superadmin',
            'permissions' => array(
                'school'    => 1,
                'user'      => 1,
                'group'    => 1,
                'event'    => 1,
            ),
        ));

        Sentry::createUser(array(
            'email'    => 'john.doe@example.com',
            'password' => 'foobar',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'activated' => true,
        ));

        $user = Sentry::findUserById(1);
        $adminGroup = Sentry::findGroupById(1);
        $user->addGroup($adminGroup);

    }

}
