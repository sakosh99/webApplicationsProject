<?php

namespace App\Http\Controllers;

use App\Http\Requests\groups\AddUserToGroupRequest;
use App\Http\Requests\groups\CreateGroupRequest;
use App\Http\Requests\groups\DeleteUserFromGroupRequest;
use App\Http\Requests\groups\DynamicSearchRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['groupNameConflict'])
            ->only('create');

        $this->middleware(['checkGroupType', 'checkUserInGroup:true', 'checkGroupPublisher'])
            ->only('addUserToGroup');

        $this->middleware(['userReservedFile', 'checkUserInGroup:false', 'checkGroupPublisher'])
            ->only('deleteUserFromGroup');

        $this->middleware(['userReservedFile', 'checkUserInGroup:false'])
            ->only('leftGroup');
        $this->middleware(['groupFilesReserved'])
            ->only('deleteGroup');
            $this->middleware(['checkRole'])
            ->only('getGroupsByUser');
    }

    public function create(CreateGroupRequest $request)
    {
        DB::beginTransaction();
        request()->transaction = true;

        Group::create([
            'group_name' => $request->group_name,
            'group_type' => $request->group_type,
            'publisher_id' => Auth::user()->id
        ]);

        DB::commit();

        return $this->successResponse(
            null,
            'Group created successfully',
        );
    }


    public function addUserToGroup(AddUserToGroupRequest $request)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', $request->group_id);
        $user = $this->findByIdOrFail(User::class, 'User', $request->user_id);

        DB::beginTransaction();
        request()->transaction = true;

        $user->inGroups()->attach($group);

        DB::commit();
        return $this->successResponse(
            null,
            'User added to group successfully',
        );
    }

    public function deleteUserFromGroup(DeleteUserFromGroupRequest $request)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', $request->group_id);
        $user = $this->findByIdOrFail(User::class, 'User', $request->user_id);

        DB::beginTransaction();
        request()->transaction = true;

        $user->inGroups()->detach($group);

        DB::commit();
        return $this->successResponse(
            null,
            'User deleted from group successfully',
        );
    }

    public function leftGroup($group_id)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', $group_id);

        DB::beginTransaction();
        request()->transaction = true;

        $files = $group->files->where('publisher_id', Auth::user()->id);


        foreach ($files as $file) {
            $file->update(['publisher_id' => $group->publisher->id]);
        }

        Auth::user()->inGroups()->detach($group);

        DB::commit();
        return $this->successResponse(
            null,
            'You left from group',
        );
    }

    public function deleteGroup($group_id)
    {
        $group = $this->findByIdOrFail(Group::class, 'File', $group_id);
        DB::beginTransaction();
        request()->transaction = true;

        $group->delete();

        DB::commit();
        return $this->successResponse(
            null,
            'Group deleted successfully',
        );
    }


    //////////////////////////////////////////////get functions///////////////////////////////////////////


    public function getUserGroups(DynamicSearchRequest $request)
    {
        $groups = Group::dynamicSearch($request->filter)->get();

        return $this->successResponse(
            GroupResource::collection($groups),
            'Groups fetched successfully',
        );
    }

    public function getGroupsByUser($user_id)//only for admin
    {
        $groups = Group::where('publisher_id', $user_id)->get();

        return $this->successResponse(
            GroupResource::collection($groups),
            'Groups fetched successfully',
        );
    }
}
