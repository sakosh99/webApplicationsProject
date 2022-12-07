<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
        $this->middleware(['checkRole'])
            ->only('getAllUsers');

        $this->middleware(['userHasPermissionOnGroup', 'checkGroupType'])
            ->only('getGroupUsers');
    }

    public function getAllUsers()
    {
        $users = $this->userService->getAllUsers();

        return $this->successResponse(
            UserResource::collection($users),
            'Users fetched successfully',
        );
    }

    public function getGroupUsers($group_id)
    {
        $users = $this->userService->getGroupUsers($group_id);

        return $this->successResponse(
            UserResource::collection($users),
            'Users fetched successfully',
        );
    }

    public function getUserProfile()
    {
        $profile = $this->userService->authenticatedUserProfile();
        return $this->successResponse(
            new UserResource($profile),
            'Password changed successfully'
        );
    }
}
