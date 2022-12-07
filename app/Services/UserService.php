<?php

namespace App\Services;

use App\RepositoryInterface\UserRepositoryInterface;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getGroupUsers($group_id)
    {
        return $this->userRepository->groupUsers($group_id);
    }

    public function authenticatedUserProfile()
    {
        return $this->userRepository->authenticatedUserProfile();
    }
}
