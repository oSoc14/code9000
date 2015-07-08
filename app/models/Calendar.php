<?php

/**
 * Part of the Sentry package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Sentry
 * @version    2.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */
class Calendar extends Cartalyst\Sentry\Groups\Eloquent\Group
{

    /**
     * Returns the group's School.
     *
     * @return  mixed
     */
    public function school()
    {
        return $this->belongsTo('School');
    }

    /**
     * Returns the appointments's of a group.
     *
     * @return  mixed
     */
    public function appointments()
    {
        return $this->hasMany('Appointment');
    }

    /**
     * Returns the creator/owner of this calendar.
     *
     * @return  mixed
     */
    public function owner()
    {
        return $this->belongsTo('User', 'owner_id', 'id');
    }

    /**
     * Returns the users (editors) of this calendar.
     *
     * @return  mixed
     */
    public function users()
    {
        return $this->belongsToMany('User', 'users_calendars', 'calendar_id', 'users_id');
    }

    /**
     * Returns the parent calendar of this calendar, can be null!
     *
     * @return  mixed
     */
    public function parent()
    {
        return $this->belongsTo('Calendar', 'parent_id', 'id');
    }

}
