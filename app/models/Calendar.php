<?php

class Calendar extends Eloquent
{

    /**
     * Returns the group's School.
     *
     * @return  mixed
     */
    public function school()
    {
        return $this->belongsTo('School','school_id','id');
    }

    /**
     * Returns the appointments's of a group.
     *
     * @return  mixed
     */
    public function appointments()
    {
        return $this->hasMany('Appointment','calendar_id','id');
    }

    /**
     * Returns the users (editors) of this calendar.
     *
     * @return  mixed
     */
    public function users()
    {
        return $this->belongsToMany('User', 'users_calendars','id','id');
    }

    /**
     * Returns the parent calendar of this calendar, can be null!
     *
     * @return  mixed
     */
    public function parent()
    {
        return $this->hasOne('Calendar', 'parent_id', 'id');
    }

}
