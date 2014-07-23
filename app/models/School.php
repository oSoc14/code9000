<?php
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

    //returns the users belonging to the school
    public function groups()
    {
        return $this->hasMany('Group');
    }

}
