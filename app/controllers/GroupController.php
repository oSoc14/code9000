<?php

class GroupController extends \BaseController {

	/**
	 * Display a listing of the groups.
	 *
	 * @return Response
	 */
	public function index()
	{
        if(Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            $groups = null;
            $schoolName = null;
            if ($user->hasAccess('school')){
                $groups = Group::where('school_id','<>','')->get();
                $schoolName = 'Grouplist';
                // Return view with selected parameters
                return View::make('group.listGroups')->with('groups',$groups)->with('schoolName',$schoolName);
            } elseif($user->hasAccess('group')){
                // Get school_id, by which we will search for related groups
                $schoolId = $user->school_id;
                // Find all groups with certain school_id
                $groups = Group::where('school_id', '=', $schoolId)->get();
                // Find selected school and get the name (which will be used as title on the view)
                $school = School::where('id', '=', $schoolId)->first();
                $schoolName = $school->name;
                // Return view with selected parameters
                return View::make('group.listGroups')->with('groups',$groups)->with('schoolName',$schoolName);
            } else {
                return Redirect::route('calendar.index');
            }
        } else {
            return Redirect::route('landing');
        }

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        if(Sentry::check()) {
            $schools = null;
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAccess('school')){
                $schools = School::lists('name','id');
                return View::make('group.createGroup')->with('schools',$schools);
            }else{
                if($user->hasAccess('group')) {
                    return View::make('group.createGroup')->with('schools',null);
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
        if(Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','user'))){
                $school = null;
                if ($user->hasAccess('school')){
                    $school = School::find(Input::get('school'));
                }else{
                    $school = $user->school;
                }
                $groupFullName = $school->short.'_'.strtolower(e(Input::get('name')));

                $validator = Validator::make(
                    array(
                        'name' => Input::get('name'),
                        'school' => Input::get('school'),
                        'permissions' => Input::get('permissions')
                    ),
                    array(
                        'name' => 'required',
                        'school' => 'integer'
                    )
                );

                $validator2 = Validator::make(
                    array(
                        'name' => $groupFullName,
                    ),
                    array(
                        'name' => 'unique:groups,name',
                    )
                );

                if ($validator->fails() || $validator2->fails()) {
                    if($validator2->fails())
                        $validator->getMessageBag()->add('name', Lang::get('validation.unique', array('attribute ' => 'name ')));

                    return Redirect::route('group.create')->withInput()->withErrors($validator);
                } else {
                    $permissions = [];
                    $permissionlist = Input::get('permissions');
                    if(isset($permissionlist)) {
                        foreach($permissionlist as $key => $value){
                            if($key != "school"){
                                $permissions[$key] = 1;
                            }
                        }
                    }
                    // Create the group
                    $group = Sentry::createGroup(array(
                        'name'        => $groupFullName,
                        'permissions' => $permissions,
                        'school_id'   => $school->id
                    ));
                    return Redirect::route('group.index');
                }
            } else {
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        }else{
            // If no permissions, redirect to calendar index
            return Redirect::route('landing');
        }
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        if(Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            if ($user->hasAnyAccess(array('school','user'))){
                // Find selected group by $id
                $group = Sentry::findGroupById($id);
                if($user->school_id == $group->school_id || $user->hasAccess('school')) {
                    // Find all users in the selected group
                    $users = Sentry::findAllUsersInGroup($group);
                    // Find all users by school
                    $schoolUsers = User::where('users.school_id', $group->school_id)->get();
                    // Find all possible users that aren't in the group yet
                    $possibleUsers = [];
                    foreach($schoolUsers as $su) {
                        $found = false;
                        foreach($users as $u ){
                            if($u->id === $su->id) {
                                $found = true;
                                break;
                            }
                        }
                        if(!$found) {
                            array_push($possibleUsers, $su);
                        }
                    }
                    // Transform array into usable list for dropdownmenu
                    $smartUsers = [];
                    foreach($possibleUsers as $pus){
                        $smartUsers[$pus->id] = $pus->email;
                    }
                    // Return view with selected parameters
                    return View::make('group.editGroups')
                        ->with('users',$users)
                        ->with('group', $group)
                        ->with('smartUsers', $smartUsers);
                } else {
                    return Redirect::route('calendar.index');
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
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if(Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            $group = Sentry::findGroupById($id);
            if ($user->hasAccess('school') || ($user->hasAccess('group') && $user->school_id == $group->school_id)){
                $school = $group->school;
                $grp = str_replace($school->short.'_','',$group->name);
                $groupFullName = strtolower($school->short.'_'.e(Input::get('name')));
                $validator = Validator::make(
                    array(
                        'name' => Input::get('name'),
                        'permissions' => Input::get('permissions')
                    ),
                    array(
                        'name' => 'required'
                    )
                );
                $validator2 = Validator::make([],[]);
                if($group->name != $groupFullName) {
                    $validator2 = Validator::make(
                        [ 'name' => $groupFullName ],
                        [ 'name' => 'unique:groups,name']
                    );
                }
                if ($validator->fails() || $validator2->fails()) {
                    if(isset($validator2)) {
                        if($validator2->fails())
                            $validator->getMessageBag()->add('name', Lang::get('validation.unique', array('attribute ' => 'name ')));
                    }
                    return Redirect::route('group.edit',$id)->withInput()->withErrors($validator);
                } elseif($grp == 'global' || $grp == 'admin') {
                    return Redirect::route('group.edit',$id);
                } else {
                    // Set default permissions
                    $permissions = ["event"=>0,"user"=>0,"group"=>0,"school"=>0];
                    $permissionlist = Input::get('permissions');

                    if(isset($permissionlist)) {
                        foreach($permissionlist as $key => $value){
                            if($key != "school"){
                                $permissions[$key] = 1;
                            }
                        }
                        $group->permissions = $permissions;
                    }
                    $group->name = $groupFullName;
                    $group->save();

                    return Redirect::route('group.edit',$id);
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(Sentry::check()) {
            // Find active user
            $user = Sentry::getUser();
            $group = Group::find($id);
            // Check if User belongs to group/school which the appointment is from
            if ($user->hasAccess('school') || ($user->hasAccess('group') && $user->school_id == $group->school_id)){
                $school = $group->school;
                $grp = str_replace($school->short.'_','',$group->name);
                // Do not allow default groups to be deleted
                if($grp == 'global' || $grp == 'admin')
                    return Redirect::back();
                else
                    $group->delete();
            }
            return Redirect::route('group.index');
        } else {
            return Redirect::route('landing');
        }
	}

}
