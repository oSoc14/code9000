<?php

/**
 * Class GroupController
 * This controller handles the CRUD of groups.
 */
class GroupController extends \BaseController
{

    /**
     * Display a listing of the groups.
     *
     * @return Response
     */
    public function index()
    {
        if (Sentry::check()) {
            // Find active user and set default variables to null
            $user       = Sentry::getUser();
            $groups     = null;
            $schoolName = null;

            // Check if user is superAdmin
            if ($user->hasAccess('school')) {
                $groups     = Group::where('school_id', '<>', '')->get();
                $schoolName = 'Grouplist';

                // Return view with selected parameters
                return View::make('group.listGroups')->with('groups', $groups)->with('schoolName', $schoolName);

            } elseif ($user->hasAccess('group')) {

                // Get school_id, by which we will search for related groups
                $schoolId = $user->school_id;

                // Find all groups with certain school_id
                $groups = Group::where('school_id', '=', $schoolId)->get();

                // Find selected school and get the name (which will be used as title on the view)
                $school     = School::where('id', '=', $schoolId)->first();
                $schoolName = $school->name;

                // Return view with selected parameters
                return View::make('group.listGroups')->with('groups', $groups)->with('schoolName', $schoolName);

            } else {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }

    }


    /**
     * Show the form for creating a new group.
     *
     * @return Response
     */
    public function create()
    {
        if (Sentry::check()) {
            $schools = null;
            // Find active user
            $user = Sentry::getUser();

            // If user is a superAdmin (has access to school), show school-dropdown for the view where the user can
            // choose which school he wants to add the group to
            if ($user->hasAccess('school')) {
                $schools = School::lists('name', 'id');

                return View::make('group.createGroup')->with('schools', $schools);

            } else {

                if ($user->hasAccess('group')) {
                    return View::make('group.createGroup')->with('schools', null);
                } else {
                    // If no permissions, redirect to calendar index
                    return Redirect::route('calendar.index');
                }
            }
        } else {
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();

            if ($user->hasAnyAccess(['school', 'group'])) {
                $school = null;

                // If user is a superAdmin (has access to school), get info from the "Input::get('school')"-field,
                // otherwise, use the school_id from the user
                if ($user->hasAccess('school')) {
                    $school = School::find(Input::get('school'));
                } else {
                    $school = $user->school;
                }

                // Generate the full groupname (schoolshort_groupshort)
                $groupFullName = $school->short . '_' . strtolower(e(str_replace(' ','_',Input::get('name'))));

                // Validate input fields
                $validator = Validator::make(
                    [
                        'name'        => e(Input::get('name')),
                        'school'      => Input::get('school'),
                        'permissions' => Input::get('permissions')
                    ],
                    [
                        'name'   => 'required',
                        'school' => 'integer'
                    ]
                );

                // Make a second validator to see if the new group name is unique
                $validator2 = Validator::make(
                    [
                        'name' => $groupFullName,
                    ],
                    [
                        'name' => 'unique:groups,name',
                    ]
                );

                // Return correct errors if validators fail
                if ($validator->fails() || $validator2->fails()) {
                    if ($validator2->fails()) {
                        $validator->getMessageBag()->add(
                            'name',
                            Lang::get('validation.unique', ['attribute ' => 'name '])
                        );
                    }

                    return Redirect::route('group.create')->withInput()->withErrors($validator);

                } else {
                    // If there are no issues, create a ne group with all the correct parameters
                    $permissions    = [];
                    $permissionlist = Input::get('permissions');

                    // If permissions aren't empty, make a key-value array that contains the permissions
                    if (isset($permissionlist)) {
                        foreach($permissionlist as $key => $value) {
                            if ($key != "school") {
                                $permissions[$key] = 1;
                            }
                        }
                    }
                    // Create the group
                    Sentry::createGroup(
                        [
                            'name'        => $groupFullName,
                            'permissions' => $permissions,
                            'school_id'   => $school->id
                        ]
                    );

                    return Redirect::route('group.index');
                }
            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            // Find selected group by $id
            $group = Sentry::findGroupById($id);

            // Permissions check
            if (($user->hasAccess('group') && $user->school_id == $group->school_id) || $user->hasAccess('school')) {

                // Find all users in the selected group
                $users = Sentry::findAllUsersInGroup($group);
                // Find all users by school
                $schoolUsers = User::where('users.school_id', $group->school_id)->get();

                // Find all possible users that aren't in the group yet
                // This array of users will be used to generate a dropdown menu
                $possibleUsers = [];
                foreach ($schoolUsers as $su) {
                    $found = false;
                    foreach ($users as $u) {
                        if ($u->id === $su->id) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        array_push($possibleUsers, $su);
                    }
                }

                // Transform array into usable list for dropdownmenu
                $smartUsers = [];
                foreach ($possibleUsers as $pus) {
                    $smartUsers[$pus->id] = $pus->email;
                }

                // Return view with selected parameters
                return View::make('group.editGroups')
                    ->with('users', $users)
                    ->with('group', $group)
                    ->with('smartUsers', $smartUsers);

            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }
    }


    /**
     * Update the specified group in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (Sentry::check()) {
            // Find active user and group information
            $user  = Sentry::getUser();
            $group = Sentry::findGroupById($id);

            // Permission checks
            if ($user->hasAccess('school') || ($user->hasAccess('group') && $user->school_id == $group->school_id)) {

                // If permissions are met, get school info
                $school = $group->school;

                // Get short group name (without the schoolShort in front of it)
                $grp = str_replace($school->short . '_', '', $group->name);

                // Generate full group name
                $groupFullName = strtolower($school->short . '_' . e(str_replace(' ','_',Input::get('name'))));

                // Make a validator to see if the new group name is unique if it's not the same as before
                // Validate input fields
                $validator = Validator::make(
                    [
                        'name' => Input::get('name'),
                        'school' => Input::get('school'),
                        'permissions' => Input::get('permissions')
                    ],
                    [
                        'name' => 'required',
                        'school' => 'integer'
                    ]
                );
                // Make a second validator to see if the new group name is unique
                $validator2 = Validator::make(
                    [
                        'name' => $groupFullName,
                    ],
                    [
                        'name' => 'unique:groups,name',
                    ]
                );

                // Error handling
                if ($validator->fails() || $validator2->fails()) {
                    if($validator2->fails()) {
                        $validator->getMessageBag()->add('name', Lang::get('validation.unique', ['attribute ' => 'name ']));
                    }

                    return Redirect::route('group.create')->withInput()->withErrors($validator);


                } elseif ($grp == 'global' || $grp == 'admin') {
                    // Do not allow default groups to be renamed
                    return Redirect::route('group.edit', $id);

                } else {
                    // Set default permissions (have to be set to 0 otherwise we can't reset them if needed with Sentry
                    $permissions    = ["event" => 0, "user" => 0, "group" => 0, "school" => 0];
                    $permissionlist = Input::get('permissions');

                    // Loop through permission checkboxes from input, and put them in key)value pairs which are to be
                    // inserted in the database
                    if (isset($permissionlist)) {
                        foreach ($permissionlist as $key => $value) {
                            if ($key != "school") {
                                $permissions[$key] = 1;
                            }
                        }
                        $group->permissions = $permissions;
                    }

                    $group->name = $groupFullName;
                    // Save/update the group
                    $group->save();

                    return Redirect::route('group.edit', $id);
                }
            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        } else {
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }
    }


    /**
     * Remove the specified group from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Sentry::check()) {
            // Find active user
            $user  = Sentry::getUser();
            $group = Group::find($id);

            // Check if User belongs to group/school which the appointment is from
            if ($user->hasAccess('school') || ($user->hasAccess('group') && $user->school_id == $group->school_id)) {

                $school = $group->school;
                $grp    = str_replace($school->short . '_', '', $group->name);

                // Do not allow default groups to be deleted
                if ($grp == 'global' || $grp == 'admin') {
                    return Redirect::back();
                } else {
                    $group->delete();
                }
            }

            return Redirect::route('group.index');
        } else {
            return Redirect::route('landing');
        }
    }

}
