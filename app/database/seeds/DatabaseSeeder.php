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


        School::create(array(
            'name'=>'Artevelde Mariakerke',
        ));

        Sentry::createGroup(array(
            'name'        => 'SchoolAdmin',
            'permissions' => array(
                'school' => 0,
                'admin' => 0,
                'groups' => 1,
                'users' => 1,
                'events' => 1,
            ),
        ));

        Sentry::register(array(
            'email'    => 'test@example.com',
            'password' => 'foobar',
            'activated' => true,
            'school_id' => 1,
        ));

        $user = Sentry::findUserById(1);
        $adminGroup = Sentry::findGroupById(1);
        $user->addGroup($adminGroup);
	}

}
