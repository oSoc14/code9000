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
                'admin'     => 1,
                'user'      => 1,
                'groups'    => 1,
                'events'    => 1,
            ),
        ));

        Sentry::createGroup(array(
            'name'        => 'hettrappenhuis_schooladmin',
            'permissions' => array(
                'school'    => 0,
                'admin'     => 1,
                'user'      => 1,
                'groups'    => 1,
                'events'    => 1,
            ),
            'school_id'     => 1,
        ));

        Sentry::createGroup(array(
            'name'        => 'hettrappenhuis_global',
            'permissions' => array(
                'school'    => 0,
                'admin'     => 0,
                'user'      => 0,
                'groups'    => 0,
                'events'    => 0,
            ),
            'school_id'     => 1,
        ));
    }

}
