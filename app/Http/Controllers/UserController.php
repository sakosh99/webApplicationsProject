<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Exception;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkRole'])
            ->only('getAllUsers');

        $this->middleware(['userHasPermissionOnGroup'])
            ->only('getGroupUsers');
    }

    public function getAllUsers()
    {
        $users = User::all();

        return $this->successResponse(
            UserResource::collection($users),
            'Users fetched successfully',
        );
    }

    public function getGroupUsers($group_id)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', $group_id);

        if ($group->group_type == 'public') {
            throw new Exception(
                'This group contains by default all users',
                403
            );
        }
        return $this->successResponse(
            UserResource::collection($group->users),
            'Users fetched successfully',
        );
    }
}
