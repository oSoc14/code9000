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
class User extends Cartalyst\Sentry\Users\Eloquent\User
{


    protected $appends = array('calendar_ids');

    protected $table = 'users';

    protected static $groupModel = 'Role';

    protected static $userGroupsPivot = 'user_roles';

    /**
     * Returns the user's School.
     *
     * @return  mixed
     */
    public function school()
    {
        return $this->belongsTo('School','school_id','id');
    }


    /**
     * Returns the user's Calendars.
     *
     * @return  mixed
     */
    public function calendars()
    {
        return $this->belongsToMany('Calendar', 'users_calendars');
    }


    /**
     * Returns the user's Role.
     *
     * @return  mixed
     */
    public function role()
    {
        return $this->belongsToMany('Role','user_roles','id','id');
    }


    public function getCalendarIdsAttribute()
    {
        $array = array();
        // load in a separate object, so we can only send this on json without sending the complete list of calendars
        $user = User::find($this->id);
        foreach ($user->calendars as $calendar) {
            array_push($array, $calendar->id);
        }

        return $array;
    }
}
