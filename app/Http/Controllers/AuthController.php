<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\ChangePasswordRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        request()->transaction = true;

        
        $user = User::create(array_merge($request->validated(),[
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]));
        // $user = User::create([
        //     ...$request->validated(),
        //     'password' => Hash::make($request->password),
        //     'role' => 'user'
        // ]);
        Group::create([
            'group_name' => $user->user_name,
            'group_type' => 'private',
            'publisher_id' => $user->id
        ]);

        DB::commit();

        $token = Auth::attempt([
            'user_name' => $request->user_name,
            'password' => $request->password
        ]);
        return $this->successResponse(
            null,
            'Successfully registered',
            200,
            $token
        );
    }

    public function login(LoginRequest $request)
    {
        if (!$token = Auth::attempt($request->validated())) {
            throw new Exception('Invalid credentials', 401);
        }

        return $this->successResponse(
            null,
            'Logged in successfully',
            200,
            $token
        );
    }


    public function logout()
    {
        Auth::logout();
        return $this->successResponse(
            null,
            'Logged out successfully'
        );
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $this->findByIdOrFail(User::class, 'User', Auth::user()->id);

        DB::beginTransaction();
        request()->transaction = true;

        $user->password = Hash::make($request->new_password);
        $user->save();

        DB::commit();
        return $this->successResponse(
            null,
            'Password changed successfully'
        );
    }

    public function getUserProfile()
    {
        return $this->successResponse(
            new UserResource(Auth::user()),
            'Password changed successfully'
        );
    }
}
