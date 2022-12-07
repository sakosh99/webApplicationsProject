<?php

namespace App\Http\Controllers;

use App\Http\Requests\groups\AddUserToGroupRequest;
use App\Http\Requests\groups\CreateGroupRequest;
use App\Http\Requests\groups\DeleteUserFromGroupRequest;
use App\Http\Requests\groups\DynamicSearchRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\GroupRepositoryInterface;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService)
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
        $this->groupService->create($request);

        return $this->successResponse(
            null,
            'Group created successfully',
        );
    }


    public function addUserToGroup(AddUserToGroupRequest $request)
    {
        $this->groupService->addUser($request);

        return $this->successResponse(
            null,
            'User added to group successfully',
        );
    }

    public function deleteUserFromGroup(DeleteUserFromGroupRequest $request)
    {
        $this->groupService->deleteUser($request);

        return $this->successResponse(
            null,
            'User deleted from group successfully',
        );
    }

    public function leftGroup($group_id)
    {
        $this->groupService->leftGroup($group_id);
        return $this->successResponse(
            null,
            'You left from group',
        );
    }

    public function deleteGroup($group_id)
    {
        $this->groupService->delete($group_id);

        return $this->successResponse(
            null,
            'Group deleted successfully',
        );
    }


    //////////////////////////////////////////////get functions///////////////////////////////////////////


    public function getUserGroups(DynamicSearchRequest $request)
    {
        $groups = $this->groupService->userGroups($request);
        return $this->successResponse(
            GroupResource::collection($groups),
            'Groups fetched successfully',
        );
    }

    public function getGroupsByUser($user_id) //only for admin
    {
        $groups = $this->groupService->GroupsByUserId($user_id);

        return $this->successResponse(
            GroupResource::collection($groups),
            'Groups fetched successfully',
        );
    }
}
