<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class AppParent extends Eloquent  {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'parent_appointments';

    //returns the group the appointment belongs to
    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function appointments()
    {
        return $this->hasMany('Appointment');
    }
}
