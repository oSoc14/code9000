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
            }elseif($user->hasAccess('group')){
                // Get school_id, by which we will search for related groups
                $schoolId = $user->school_id;
                // Find all groups with certain school_id
                $groups = Group::where('school_id', '=', $schoolId)->get();
                // Find selected school and get the name (which will be used as title on the view)
                $school = School::where('id', '=', $schoolId)->first();
                $schoolName = $school->name;
                // Return view with selected parameters
                return View::make('group.listGroups')->with('groups',$groups)->with('schoolName',$schoolName);
            }else{
                return Redirect::route('calendar.index');
            }
        }else{
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
            if ($user->hasAnyAccess(array('school','user'))){
                $schools = School::lists('name','id');
                return View::make('group.createGroup')->with('schools',$schools);
            }else{
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        }else{
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
                $validator = Validator::make(
                    array(
                        'name' => $school->short.'_'.Input::get('name'),
                        'school' => Input::get('school'),
                        'permissions' => Input::get('permissions')
                    ),
                    array(
                        'name' => 'required|unique:groups,name',
                        'school' => 'integer'
                    )
                );
                if ($validator->fails())
                {
                    return Redirect::route('group.create')->withInput()->withErrors($validator);
                }
                else{
                    $prefix = '';
                    $prefix = $school->short.'_';

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
                        'name'        => $prefix.strtolower(Input::get('name')),
                        'permissions' => $permissions,
                        'school_id' => $school->id
                    ));
                    return Redirect::route('group.index');
                }
            }else{
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
                // TODO: Remove users from group
                // Find selected group by $id
                $group = Sentry::findGroupById($id);
                // Find all users in the selected group
                $users = Sentry::findAllUsersInGroup($group);
                // Find all users by school
                $schoolUsers = User::where('users.school_id', $group->school_id)->get();

                // Find all possible users that aren't in the group yet
                $possibleUsers = [];
                foreach($schoolUsers as $su) {
                    $found = false;
                    foreach($users as $u ){
                        if($u->id === $su->id)
                        {
                            $found = true;
                            break;
                        }
                    }
                    if(!$found){
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
            }else{
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        }else{
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
            if ($user->hasAnyAccess(array('school','user'))){
                $group = Sentry::findGroupById($id);

                $school = $group->school;

                $groupFullName = strtolower($school->short.'_'.Input::get('name'));
                $validator = Validator::make(
                    array(
                        'name' => $groupFullName,
                        'permissions' => Input::get('permissions')
                    ),
                    array(
                        'name' => 'required'
                    )
                );
                if($group->name != $groupFullName) {
                    try
                    {
                        $grp = Sentry::findGroupByName($groupFullName);
                        // Add an error message in the message collection (MessageBag instance)
                        $validator->getMessageBag()->add('name', Lang::get('validation.unique', array('attribute ' => 'name ')));

                    }
                    catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
                    {
                        $group->name = $groupFullName;
                    }
                }
                if ($validator->fails())
                {
                    return Redirect::route('group.edit',$id)->withInput()->withErrors($validator);
                }
                else{
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
                    $group->save();

                    return Redirect::route('group.edit',$id);
                }
            }else{
                // If no permissions, redirect to calendar index
                return Redirect::route('calendar.index');
            }
        }else{
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
		//
	}




}
