<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * Class Appointment
 *
 * An appointment is an "event", and belongs to a calendar. An appointment can inherit from a parent event,
 * for example in the case of recurring events, all events can be grouped to one parent.
 */
class Appointment extends Eloquent
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'appointments';

    //returns the group the appointment belongs to
    public function calendar()
    {
        return $this->belongsTo('Calendar', 'calendar_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('Appointment','parent_id','id');
    }

}
