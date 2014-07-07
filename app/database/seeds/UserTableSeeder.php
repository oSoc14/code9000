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
            'first_name' => 'John',
            'last_name' => 'Doe',
            'activated' => true,
        ));

        Sentry::createUser(array(
            'email'    => 'test@example.com',
            'password' => 'foobar',
            'activated' => true,
            'first_name' => 'TestUser',
            'last_name' => '1',
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
