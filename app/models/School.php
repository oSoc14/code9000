<?php

class School extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schools';

    //returns the users belonging to the school
    public function users()
    {
        return $this->hasMany('User','school_id','id');
    }

    //returns the calendars belonging to the school
    public function calendars()
    {
        return $this->hasMany('Calendar','school_id','id');
    }

}
