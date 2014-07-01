<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');

        Sentry::createGroup(array(
            'name'        => 'Admins',
            'permissions' => array(
                'school' => 1,
                'admin' => 1,
                'groups' => 1,
                'users' => 1,
                'events' => 1,
            ),
        ));

        Sentry::register(array(
            'email'    => 'john.doe@example.com',
            'password' => 'foobar',
            'activated' => true,
        ));

        $user = Sentry::findUserById(1);
        $adminGroup = Sentry::findGroupById(1);
        $user->addGroup($adminGroup);
	}

}
