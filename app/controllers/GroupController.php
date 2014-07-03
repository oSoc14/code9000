<?php

class GroupController extends \BaseController {

	/**
	 * Display a listing of the groups.
	 *
	 * @return Response
	 */
	public function index()
	{
        // Find active user
        $user = Sentry::getUser();
        // Get school_id, by which we will search for related groups
        $schoolId = $user->school_id;
        // Find all groups with certain school_id
        $groups = Group::where('school_id', '=', $schoolId)->get();
        // Find selected school and get the name (which will be used as title on the view)
        $school = School::where('id', '=', $schoolId)->first();
        $schoolName = $school->name;
        // Return view with selected parameters
        return View::make('group.listGroups')->with('groups',$groups)->with('schoolName',$schoolName);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        // TODO: Remove users from group
        // Find selected group by $id
        $group = Sentry::findGroupById($id);
        // Find all users in the selected group
        $users = Sentry::findAllUsersInGroup($group);
        // Get the groupname (which will be used as title in the view)
        $groupName = $group->name;
        // Return view with selected parameters
        return View::make('group.editGroups')->with('users',$users)->with('groupName', $groupName);
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
