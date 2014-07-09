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

        School::create(array(
            'name'        => 'Het Trappenhuis',
            'short'       => 'hettrappenhuis',
            'city'        => 'Gent'
        ));
    }

}
