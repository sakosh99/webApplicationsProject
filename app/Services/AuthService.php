<?php

namespace App\Services;

use App\RepositoryInterface\GroupRepositoryInterface;
use App\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GroupRepositoryInterface $groupRepository,
    ) {
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        request()->transaction = true;

        $user = $this->userRepository->create(array_merge($request->validated(), [
            'password' => Hash::make($request->password),
            'role' => 'user',
            'subscription_plan_id' => 1
        ]));

        $this->groupRepository->create([
            'group_name' => $user->user_name,
            'group_type' => 'private',
            'publisher_id' => $user->id
        ]);

        DB::commit();

        return $user;
    }
    public function changePassword($validatedRequest)
    {
        DB::beginTransaction();
        request()->transaction = true;

        $this->userRepository->update(
            Auth::user()->id,
            ['password' => Hash::make($validatedRequest['new_password'])]
        );

        DB::commit();
    }
}
