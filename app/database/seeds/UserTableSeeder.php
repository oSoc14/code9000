<?php

class UserTableSeeder extends Seeder {

    /**
     * Run the groupTable seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        Sentry::createUser(array(
            'email'    => 'john.doe@example.com',
            'password' => 'foobar',
            'activated' => true,
        ));

        Sentry::createUser(array(
            'email'    => 'test@example.com',
            'password' => 'foobar',
            'activated' => true,
            'school_id' => 1,
        ));

        $user = Sentry::findUserById(1);
        $adminGroup = Sentry::findGroupById(1);
        $user->addGroup($adminGroup);

        $user = Sentry::findUserById(2);
        $adminGroup = Sentry::findGroupById(2);
        $user->addGroup($adminGroup);
    }

}
