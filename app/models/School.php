<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class School extends Eloquent  {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'schools';

    //returns the users belonging to the school
    public function users()
    {
        return $this->hasMany('User');
    }

}
