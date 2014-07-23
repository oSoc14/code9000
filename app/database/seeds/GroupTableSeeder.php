<?php

class GroupTableSeeder extends Seeder {

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
    }

}
