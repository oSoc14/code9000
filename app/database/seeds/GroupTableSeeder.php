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

        Sentry::createGroup(array(
            'name'        => 'hettrappenhuis_admin',
            'permissions' => array(
                'school'    => 0,
                'user'      => 1,
                'group'    => 1,
                'event'    => 1,
            ),
            'school_id'     => 1,
        ));

        Sentry::createGroup(array(
            'name'        => 'hettrappenhuis_global',
            'permissions' => array(
                'school'    => 0,
                'user'      => 0,
                'group'    => 0,
                'event'    => 1,
            ),
            'school_id'     => 1,
        ));
    }

}
