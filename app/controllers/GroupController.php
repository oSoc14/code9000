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
                $groups = Group::get();
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
            if ($user->hasAnyAccess(array('school','user')))            {
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
                $validator = Validator::make(
                    array(
                        'name' => Input::get('name'),
                        'school' => Input::get('school')
                    ),
                    array(
                        'name' => 'required',
                        'school' => 'integer'
                    )
                );
                if ($validator->fails())
                {
                    return Redirect::route('group.createGroup')->withInput()->withErrors($validator);
                }
                else{
                    $school=null;
                    $prefix = '';
                    if ($user->hasAccess('school')){
                        $school = School::find(Input::get('school'));
                        $prefix = $school->short.'_';
                    }else{
                        $school = $user->school;
                    }
                    // Create the group
                    $group = Sentry::createGroup(array(
                        'name'        => $prefix.strtolower(Input::get('name')),
                        'permissions' => array(
                            'school' => 0,
                            'group' => 0,
                            'users' => 0,
                            'event' => 1,
                        ),
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
		//
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
