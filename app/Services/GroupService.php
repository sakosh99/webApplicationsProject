<?php

namespace App\Services;

use App\RepositoryInterface\FileRepositoryInterface;
use App\RepositoryInterface\GroupRepositoryInterface;
use App\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function __construct(
        private GroupRepositoryInterface $groupRepository,
        private UserRepositoryInterface $userRepository,
        private FileRepositoryInterface $fileRepository
    ) {
    }

    public function create($validatedRequest)
    {
        DB::beginTransaction();
        request()->transaction = true;

        $group = $this->groupRepository->create([
            'group_name' => $validatedRequest['group_name'],
            'group_type' => $validatedRequest['group_type'],
            'publisher_id' => Auth::user()->id
        ]);

        DB::commit();

        return $group;
    }
    public function addUser($validatedRequest)
    {
        $group = $this->groupRepository->find($validatedRequest['group_id']);
        $user = $this->userRepository->findByUserNameOrEmail($validatedRequest['emailOrUserName']);

        DB::beginTransaction();
        request()->transaction = true;

        $this->groupRepository->attachUser($user, $group);

        DB::commit();
    }
    public function deleteUser($validatedRequest)
    {
        $group = $this->groupRepository->find($validatedRequest['group_id']);
        $user = $this->userRepository->find($validatedRequest['user_id']);

        DB::beginTransaction();
        request()->transaction = true;

        $this->groupRepository->detachUser($user, $group);

        DB::commit();
    }
    public function leftGroup($group_id)
    {
        $group = $this->groupRepository->find($group_id);

        DB::beginTransaction();
        request()->transaction = true;

        $files = $this->fileRepository->groupFilesForUser($group);

        foreach ($files as $file) {
            $this->fileRepository->update(
                $file,
                ['publisher_id' => $group->publisher->id]
            );
        }
        $this->groupRepository->detachUser(Auth::user(), $group);

        DB::commit();
    }
    public function delete($group_id)
    {
        $group = $this->groupRepository->find($group_id);
        DB::beginTransaction();
        request()->transaction = true;

        $this->groupRepository->delete($group);

        DB::commit();
    }
    public function userGroups($validatedRequest)
    {
        $groups = $this->groupRepository->userGroups($validatedRequest['filter']);
        return $groups;
    }
    public function GroupsByUserId($user_id)
    {
        $user = $this->userRepository->find($user_id);
        $groups = $this->groupRepository->groupsByUserId($user);
        return $groups;
    }
}
