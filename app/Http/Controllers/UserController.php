<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\UserRepositoryInterface;
use Exception;


class UserController extends Controller
{
    private $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware(['checkRole'])
            ->only('getAllUsers');

        $this->middleware(['userHasPermissionOnGroup', 'checkGroupType'])
            ->only('getGroupUsers');
    }

    public function getAllUsers()
    {
        $users = $this->userRepository->all();

        return $this->successResponse(
            UserResource::collection($users),
            'Users fetched successfully',
        );
    }

    public function getGroupUsers($group_id)
    {
        $users = $this->userRepository->groupUsers($group_id);

        return $this->successResponse(
            UserResource::collection($users),
            'Users fetched successfully',
        );
    }
}
