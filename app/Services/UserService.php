<?php

namespace App\Services;

use App\RepositoryInterface\GroupRepositoryInterface;
use App\RepositoryInterface\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GroupRepositoryInterface $groupRepository
    ) {
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getGroupUsers($group_id)
    {
        $group = $this->groupRepository->find($group_id);
        return $this->userRepository->groupUsers($group);
    }

    public function authenticatedUserProfile()
    {
        return $this->userRepository->authenticatedUserProfile();
    }
}
